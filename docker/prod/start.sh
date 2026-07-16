#!/bin/sh

set -e


echo "Running migrations..."

php bin/console doctrine:migrations:migrate \
    --no-interaction || true


echo "Starting php-fpm..."

php-fpm -D


echo "Starting nginx..."

nginx -g "daemon off;"
