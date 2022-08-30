# Dockerfile
FROM php:8.1.8-apache

RUN docker-php-ext-install pdo_mysql
RUN pecl install redis && docker-php-ext-enable redis

RUN a2enmod rewrite

ADD . /var/www
ADD ./public /var/www/html

RUN chmod -R 777 /var/www/storage