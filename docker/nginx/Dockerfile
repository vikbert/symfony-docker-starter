FROM alpine:3.12

ARG DOCKER_ENV

MAINTAINER Vincent Composieux <vincent.composieux@gmail.com>

RUN apk add --update nginx
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

ADD nginx.conf /etc/nginx/
ADD symfony_$DOCKER_ENV.conf /etc/nginx/conf.d/
RUN rm /etc/nginx/conf.d/default.conf

RUN echo "upstream php-upstream { server php:9001; }" > /etc/nginx/conf.d/upstream.conf

RUN adduser -D -g '' -G www-data www-data

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
