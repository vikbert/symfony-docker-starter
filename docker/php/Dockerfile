FROM alpine:3.13.5

RUN apk add --update \
    php7-amqp \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-fileinfo\
    php7-fpm \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-intl \
    php7-json \
    php7-mbstring \
    php7-mcrypt \
    php7-mysqli \
    php7-opcache \
    php7-openssl \
    php7-pcntl \
    php7-pdo \
    php7-pdo_pgsql \
    php7-pdo_sqlite \
    php7-phar \
    php7-session \
    php7-simplexml \
    php7-soap \
    php7-sodium \
    php7-tokenizer \
    php7-xdebug \
    php7-xml \
    php7-xmlreader \
    php7-xmlwriter \
    php7-zip \
    php7-zlib \
    php7-posix \
    php7-pcntl \
    make \
    curl

COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer

RUN composer self-update --2 && rm -rf /var/cache/apk/* && rm -rf /tmp/*

ADD symfony.ini /etc/php7/conf.d/
ADD symfony.ini /etc/php7/cli/conf.d/
ADD xdebug.ini  /etc/php7/conf.d/90_xdebug.ini

ADD symfony.pool.conf /etc/php7/php-fpm.d/

CMD ["php-fpm7", "-F"]

#ENV SYMFONY_PHPUNIT_VERSION=8.3

WORKDIR /var/www
EXPOSE 9001
