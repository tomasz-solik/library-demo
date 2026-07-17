#!/bin/sh

set -e

echo "[TOMSOL] Installing dependencies..."
composer install \
    --no-interaction \
    --prefer-dist \
    --no-progress


echo "[TOMSOL] Waiting for database..."
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1
do
    sleep 2
done


echo "[TOMSOL] Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction


echo "[TOMSOL] Starting PHP-FPM..."
exec php-fpm
