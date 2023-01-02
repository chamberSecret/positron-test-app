FROM php:8.1-cli

RUN apt-get update && apt-get install -y wget \
    zlib1g-dev \
    libmcrypt-dev \
    libzip-dev \
    libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install -j$(nproc) gd

RUN apt-get -y update

RUN docker-php-ext-install pdo pdo_mysql zip

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet
RUN useradd -d /home/develop -m -s /bin/bash develop && echo "develop:develop" | chpasswd && adduser develop sudo
WORKDIR /project
RUN chown -R develop:develop /project
USER develop