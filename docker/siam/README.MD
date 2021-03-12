<div align="center">
  <h1>SIAM Oauth2 Fake Server</h1>
  <img src="https://dev.azure.com/schwarzit/a93859d1-1284-447d-9b34-67bc9cd2f7e4/_apis/git/repositories/eb41d8c5-f9c8-4aad-8dec-2bcaa02b5a04/items?path=%2Fviews%2Foauth2.png&versionDescriptor%5BversionOptions%5D=0&versionDescriptor%5BversionType%5D=0&versionDescriptor%5Bversion%5D=main&resolveLfs=true&%24format=octetStream&api-version=5.0" width=100 />
</div>

<h4 align="center">A fake SIAM Server with Oauth2 API</h4>

<p align="center">
  <a href="#intro">Intro</a> •
  <a href="#getting-started">Getting Started</a> •
  <a href="#setup">Setup</a> •
  <a href="#usage">Usage</a> •
  <a href="#license">License</a>
</p>

---

# Intro
> This is only for development purpose and not at all production ready

<img src="https://dev.azure.com/schwarzit/a93859d1-1284-447d-9b34-67bc9cd2f7e4/_apis/git/repositories/eb41d8c5-f9c8-4aad-8dec-2bcaa02b5a04/items?path=%2Fviews%2Fsiam.png&versionDescriptor%5BversionOptions%5D=0&versionDescriptor%5BversionType%5D=0&versionDescriptor%5Bversion%5D=main&resolveLfs=true&%24format=octetStream&api-version=5.0" />

This SIAM Oauth2 Fake Server helps you to do that by creating a simple and standalone Oauth2.0 Server that follows the *Authorization Code Grant* flow.
> Note: This server is not as secure and 100% compliant with Oauth2.0 guidelines.

- Authorization Code Grant flow of Oauth 2.0
 - Can generate *access token* and *refresh_token*
 - Can validate User *email* and *password* to simulate authorization.
 - Can validate *access_token*
 - Can get userinfo using *access_token*

# Getting Started
To clone and run this application, you'll need [Git](https://git-scm.com/) and [Node.js](https://nodejs.org/en/download/) (which comes with [npm](http://npmjs.com/)) installed on your computer. From your command line:
```

# Clone this repository
$ git clone https://schwarzit@dev.azure.com/schwarzit/schwarzit.phpc/_git/siam-oauth2-fake-server

# Go into the repository
$ cd siam-oauth2-fake-server

# Install dependencies
$ npm install

# Run the server
$ docker-compose up -d

# Server started at http://localhost:1234

```

#  Setup
### Environment Setup (Optional)
| **Variable**  | **Default Value**            | *Customizable* |
|---------------|------------------------------|----------------|
| PORT          | 1234                         | YES            |
| CLIENT_ID     | fakeclientid                 | YES            |
| CLIENT_SECRET | fakeclientsecret             | YES            |
| CLIENT_SCOPE  | siam                         | YES            |
| REDIRECT_URI  | https://localhost/siam_check | YES            |
| USER_EMAIL    | siam@siam.siam               | YES            |
| USER_PASSWORD | siam                         | YES            |

- The REDIRECT_URI is an array so if you need more than 1 *redirect_uri* keep adding them in array separated by a coma.
- You can change this values in the **.env** file.

### User Credential Setup (Optional)

| **Field** | **Default Value**  | **Customizable** |
| --------- | ------------------ | ---------------- |
| email     | siam@siam.siam     | YES              |
| password  | siam               | YES              |

- There is no signup method so only one user can be used at max.
- You can use the default user to test.
- The user credentials need to be manually entered into the **User.json** file.
- You can set the *Email* and *Password* to anything.

<img src="https://dev.azure.com/schwarzit/a93859d1-1284-447d-9b34-67bc9cd2f7e4/_apis/git/repositories/eb41d8c5-f9c8-4aad-8dec-2bcaa02b5a04/items?path=%2Fviews%2Flogin.png&versionDescriptor%5BversionOptions%5D=0&versionDescriptor%5BversionType%5D=0&versionDescriptor%5Bversion%5D=main&resolveLfs=true&%24format=octetStream&api-version=5.0" />



# Usage

### GET ``` /authz ```
 - The client sends request here with :
	 - *client_id*
	 - *state*
	 - *redirect_uri*
- The server verifies the details.
- Redirects to login page where the user enters *Email* and *Password*.
- If user details and all parameters are correct then the server redirects to the *redirect_uri* along with **Authorization Code** .
```
GET /authorize HTTP/1.1
Host: localhost:3000
Content-Type: application/x-www-form-urlencoded

client_id=fakeclientid
&state=abcd1234
&redirect_uri=https://your_siam_check_redirect_uri
```
### POST ``` /token ```
- The client send request here with :
	-	*client_id*
	-	*grant_type* ( authorization_code or refresh_token )
	-	*code* ( that was received in the previous step -> only if first time )
	-	*refresh_token* ( only if *access_token* needs to be refreshed )
- The server verifies the details.
- Return the generated tokens according to *grant_type* :
	-  authorization_code =>
		- new **access_token** ( signed JWT token with 24hrs validity )
		- new **refresh_token** ( long term validity )
	-  refresh_token =>
		- new **access_token** ( previous one is revoked )
```
POST /token HTTP/1.1
Host: localhost:3000
Content-Type: application/x-www-form-urlencoded

client_id=fakeclientid
&grant_type=authorization_code
&code=bc2c6f28f0gr
```
```
POST /token HTTP/1.1
Host: localhost:3000
Content-Type: application/x-www-form-urlencoded

client_id=fakeclientid
&grant_type=refresh_token
&refresh_token=69d8f97dffcababab21903b8419dc392bad1a021
```

### GET ```/userinfo```
-  ```Bearer <access_token>``` for authorization.
- The server responds with the User Info ( email only in this case ).
```
GET /userinfo HTTP/1.1
Host: localhost:3000
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIyNTRhNWFjYWVlNDcyODExODUyOGNkMDk0MjRhNDRkOCIsImlhdCI6MTU5MTAzMzE1NiwiZXhwIjoxNTkxMTE5NTU2fQ.CB0Htndz6lqMdlB4nbCNiYwq1FL23Z_QSwLf3pONO1g
```

# License
Xun.Zhou@mail.schwarz (UNLICENSED)


