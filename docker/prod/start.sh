#!/bin/sh

set -e

echo "Fix permissions..."

chmod -R 777 var

echo "Running migrations..."

php bin/console doctrine:migrations:migrate \
    --no-interaction || true


echo "Clearing cache..."

php bin/console cache:clear --env=prod


echo "Starting php-fpm..."

php-fpm -D


echo "Starting nginx..."

nginx -g "daemon off;"
