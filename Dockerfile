FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

RUN apk update && apk add --no-cache \
    bash \
    git \
    curl \
    sqlite-dev \
    libzip-dev \
    zip unzip \
    icu-dev

RUN docker-php-ext-install -j$(nproc) pdo_mysql pdo_sqlite zip intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.* .

RUN composer install \
    --no-autoloader \
    --prefer-dist \
    --ansi \
    --no-dev \
    --profile \
    --no-cache \
    --no-scripts \
    --no-interaction \
    --no-progress

COPY . .

RUN chmod -R 755 ./storage

RUN set -xe \
    && composer install || true \
    && php artisan optimize:clear --ansi || true

EXPOSE 9000
