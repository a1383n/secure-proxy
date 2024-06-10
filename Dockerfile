FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

RUN apk update && apk add --no-cache \
    bash \
    git \
    curl \
    sqlite-dev \
    libzip-dev \
    zip unzip \
    icu-dev \
    autoconf \
    gcc \
    g++ \
    make

RUN docker-php-ext-install -j$(nproc) pdo_mysql pdo_sqlite zip intl pcntl && pecl install redis && docker-php-ext-enable redis

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

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN set -xe \
    && composer dump-autoload || true \
    && php artisan optimize:clear --ansi || true

COPY ./docker/docker-entrypoint.sh /entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/bin/bash", "/entrypoint.sh"]
