FROM php:fpm-alpine

RUN apk --update --no-cache add git

# MySQL
#RUN docker-php-ext-install pdo_mysql
# MySQL

# PostgreSQL
RUN set -ex && apk --no-cache add postgresql-dev
RUN docker-php-ext-install pdo_pgsql
# PostgreSQL

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

WORKDIR /var/www

CMD composer -V;  php-fpm

EXPOSE 9000
