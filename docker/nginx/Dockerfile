# size: 22 MB
FROM nginx:alpine

WORKDIR /var/www

ADD nginx.conf /etc/nginx
ADD sites /etc/nginx/sites-available
ADD conf.d /etc/nginx/conf.d

CMD ["nginx"]

EXPOSE 80 443
