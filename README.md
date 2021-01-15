# SSO Proof Of Concept

A Proof-of-concept of Single-Sign-On(SSO) in a running Symfony 5 application.


## Start the containers
```
git clone https://github.com/vikbert/ssomoc.git
cd ssomoc
docker-compose up -d
```

## Compose Install

```bash
docker-compose exec php composer install
```


```
docker-compose run php-fpm bin/console doctrine:fixtures:load
```

## Web
```bash
http://localhost
```
