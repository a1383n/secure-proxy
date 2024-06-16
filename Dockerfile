FROM dunglas/frankenphp:php8.2-alpine

ENV SERVER_NAME=:80

WORKDIR /app/public

RUN install-php-extensions \
        pcntl \
    	pdo_mysql \
    	gd \
    	intl \
    	zip \
    	opcache \
        redis


RUN (curl -f https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer) && composer -V
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

RUN chown -R www-data:www-data ./storage ./bootstrap/cache

RUN set -xe \
    && composer dump-autoload || true \
    && php artisan optimize:clear --ansi || true

COPY ./docker/docker-entrypoint.sh /entrypoint.sh

ENV FRANKENPHP_CONFIG="worker ./public/index.php"

EXPOSE 2019
ENTRYPOINT ["/bin/sh", "/entrypoint.sh"]
