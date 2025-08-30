FROM php:8.1-fpm

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
  libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev libicu-dev libzip-dev zlib1g-dev \
  libonig-dev libxml2-dev libcurl4-openssl-dev unzip wget git build-essential --no-install-recommends \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install -j"$(nproc)" gd intl pdo_mysql mysqli mbstring xml zip opcache \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/html
