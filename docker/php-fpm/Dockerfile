FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    wget \
    zlib1g-dev \
    libmcrypt-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN echo 'memory_limit = 256M' >> /usr/local/etc/php/conf.d/docker-php-memory_limit.ini;

RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y unzip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /project