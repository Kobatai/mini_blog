FROM php:7.1-apache

RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql

COPY ./base.conf /etc/apache2/sites-available/base.conf
COPY ./php.ini /usr/local/etc/php/