require('dotenv').config();
const express = require('express');
const bodyParser = require('body-parser');

const jwt = require('jsonwebtoken');
const crypto = require('crypto');

const fs = require('fs'); //logging
const morgan = require('morgan'); //logging

const helmet = require('helmet'); //security
const cors = require('cors'); //cross origin resource security

const app = express();
app.set('view engine', 'ejs');

let accessLogStream = fs.createWriteStream('access.log', { flags: 'a' });
app.use(morgan('combined', { stream: accessLogStream }));
app.use(helmet());
app.use(cors());
app.use(bodyParser.urlencoded({ extended: true }));

/**
 * the customizable server variables, that you can change to customize your fake siam server
 * after the change, you need restart the docker container, so that the container will reload the config
 */
const PORT = process.env.PORT;
const REDIRECT_URI = process.env.REDIRECT_URI;
const CLIENT_ID = process.env.CLIENT_ID;
const CLIENT_SECRET = process.env.CLIENT_SECRET;
const CLIENT_SCOPE = process.env.CLIENT_SCOPE;
const USER_EMAIL = process.env.USER_EMAIL;
const USER_PASSWORD = process.env.USER_PASSWORD;

let codes = [];
let tokens = [];

/**
 * GET: SIAM Login Form
 */
app.get('/login', (req, res) => {
  if (!REDIRECT_URI.includes(req.query.redirect_uri))
    return res.status(401).send({ message: 'Invalid redirect URL' });

  if (req.query.client_id !== CLIENT_ID)
    return res.status(400).send({ message: 'Invalid client ID' });

  res.render('index', {
    client_id: req.query.client_id,
    redirect_uri: req.query.redirect_uri,
    state: req.query.state ? req.query.state : '',
  });
});

/**
 * POST: handle the login form request
 * the following data will be validated, then redirect the request with the newly generated authorization code
 * - redirect URL
 * - client ID
 * - user email
 * - user password
 */
app.post('/login', async (req, res) => {
  if (!REDIRECT_URI.includes(req.body.redirect_uri))
    return res.status(401).send({ message: 'Invalid redirect URL' });

  if (req.body.client_id !== CLIENT_ID)
    return res.status(400).send({ message: 'Invalid client ID' });

  console.log(req.body.email, USER_EMAIL, req.body.password, USER_PASSWORD);

  if (req.body.email !== USER_EMAIL || req.body.password !== USER_PASSWORD)
    return res.status(401).send({ message: 'Invalid email or password' });

  const code = crypto.randomBytes(12).toString('hex');
  codes.push(code);

  const responseUrl = `${req.body.redirect_uri}?code=${code}&state=${req.body.state}`;
  console.log(`Redirecting ${responseUrl}`);

  return res.redirect(responseUrl);
});

/**
 * GET: /authz
 * validate the client query data, then redirect the client to SIAM fake login page
 * the following data will be validated:
 * - redirect url
 * - client ID
 */
app.get('/authz', (req, res) => {
  if (!REDIRECT_URI.includes(req.query.redirect_uri))
    return res.status(401).send({ message: 'Invalid redirect URL' });

  if (req.query.client_id !== CLIENT_ID)
    return res.status(400).send({ message: 'Invalid client ID' });

  const responseUrl = `/login?redirect_uri=${req.query.redirect_uri}&client_id=${req.query.client_id}&state=${req.query.state}`;

  console.log('Redirecting ' + `${responseUrl}`);

  return res.redirect(`${responseUrl}`);
});

/**
 * POST: /token
 * this will simulate the authorization-code-flow, then returns the access token to the client
 * the following data from client will be validated:
 * - client ID
 * - grant type
 * - authorization code
 */
app.post('/token', async (req, res) => {
  if (req.body.client_id !== CLIENT_ID)
    return res.status(400).send({ message: 'Invalid Client ID' });

  if (
    req.body.grant_type !== 'authorization_code' &&
    req.body.grant_type !== 'refresh_token'
  )
    return res.status(400).send({ message: 'Invalid grant type' });

  const secondsInDay = 86400; // 60 * 60 * 24
  console.log(`Grant type ${req.body.grant_type}`);

  let obj;
  if (req.body.grant_type === 'authorization_code') {
    if (!codes.includes(req.body.code))
      return res.status(401).send({ message: 'Invalid Code' });

    console.log('Code verified.');
    console.log('Issuing access and refresh tokens');

    const data = crypto.createHash('md5').update(USER_EMAIL).digest('hex');
    const access_token = jwt.sign({ sub: data }, CLIENT_SECRET, {
      expiresIn: secondsInDay,
    });
    const refresh_token = crypto.randomBytes(20).toString('hex');

    tokens.push({
      access_token: access_token,
      refresh_token: refresh_token,
    });
    codes.splice(codes.indexOf(req.body.code), 1);

    obj = {
      token_type: 'bearer',
      access_token: access_token,
      refresh_token: refresh_token,
      expires_in: secondsInDay,
    };
  } else if (req.body.grant_type === 'refresh_token') {
    const index = tokens.findIndex(
      (token) => token.refresh_token === req.body.refresh_token,
    );

    if (index === -1)
      return res.status(401).send({ message: 'Invalid refresh token' });

    console.log('Issuing access token using refresh tokens');

    const data = crypto.createHash('md5').update(USER_EMAIL).digest('hex');
    const access_token = jwt.sign({ sub: data }, CLIENT_SECRET, {
      expiresIn: secondsInDay,
    });

    tokens[index].access_token = access_token;

    obj = {
      token_type: 'bearer',
      access_token: access_token,
      expires_in: secondsInDay,
      scope: CLIENT_SCOPE,
    };
  }
  res.status(200).send(obj);
});

/**
 * GET: /userinfo
 * this will return the user information based on the given access_token by the client
 * the following data will be validated:
 * - access token
 */
app.get('/userinfo', async (req, res) => {
  try {
    const token = req.headers.authorization.substr(7);
    jwt.verify(token, CLIENT_SECRET);

    if (!tokens.some((tok) => tok.access_token === token)) {
      throw new Error();
    }

    console.log('UserInfo Fetched');
    return res.send({
      mail: USER_EMAIL,
      uid: '0469fff8-7ad2-11eb-9439-0242ac130002',
      countrycode: 'at',
      employeeid: '98716',
      givenName: 'fake_first_name',
      sn: 'fake_last_name',
      sub: '382ehud3h2398d23hdh',
      workforceID: '10003838738383',
      groups: ['fake-xx-test-revision'],
    });
  } catch {
    return res.send({ message: 'Invalid Token' });
  }
});

app.listen(PORT, () => {
  console.log('SIAM Oauth2 Server started at ' + PORT);
});
