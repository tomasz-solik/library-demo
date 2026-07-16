FROM php:8.4-fpm-alpine


RUN apk add --no-cache \
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


# najpierw pliki composera (cache dockera)
COPY composer.json composer.lock ./


RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --no-progress


# teraz kopiujemy całą aplikację Symfony
COPY . .


RUN composer dump-autoload --optimize

RUN mkdir -p var/cache var/log \
    && chmod -R 777 var

RUN php bin/console cache:clear --env=prod || true


CMD ["php-fpm"]
