FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx \
    git \
    unzip \
    libpq-dev \
    icu-dev

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    intl \
    opcache


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --no-progress


COPY . .


RUN composer dump-autoload --optimize


RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var

COPY docker/prod/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/prod/start.sh /start.sh

RUN chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]
