FROM php:fpm-alpine

RUN apk --update --no-cache add git
RUN docker-php-ext-install pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

ENV SYMFONY_PHPUNIT_VERSION=8.3
ADD symfony.ini /etc/php7/conf.d/
ADD symfony.ini /etc/php7/cli/conf.d/
ADD symfony.pool.conf /etc/php7/php-fpm.d/

ADD xdebug.ini  /etc/php7/conf.d/

WORKDIR /var/www/app

CMD composer -V;  php-fpm

EXPOSE 9001
