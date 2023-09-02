FROM php:8.2-fpm-alpine

RUN apk --no-cache add \
        --update linux-headers \
        icu-dev \
        libpng-dev \
        libzip-dev \
        gcc g++ make autoconf \
        bash \
    && docker-php-ext-install exif \
    && docker-php-ext-install gd \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install opcache

RUN pecl install xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

WORKDIR /var/www/app

