FROM php:7.2-fpm

ARG USERID
ARG GROUPID

RUN apt-get update \
    && apt-get install -y curl wget zlib1g-dev git zip vim libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev \
            libpng-dev libicu-dev libpq-dev libxpm-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install gd \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install exif \
    && docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
        --with-xpm-dir=/usr/lib/x86_64-linux-gnu/ \
        --with-vpx-dir=/usr/lib/x86_64-linux-gnu/ \
    && mkdir /app \
    && usermod -u $USERID -d /app www-data \
    && chown www-data:www-data /app \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /tmp/pear \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR "/app"
USER www-data