#!/bin/sh

set -e

echo "[TOMSOL] PHP modules:"
php -m | grep -E "pdo|pgsql"

echo "[TOMSOL] DATABASE:"
echo $DATABASE_URL

echo "[TOMSOL] Database check..."
php bin/console doctrine:database:create \
    --if-not-exists \
    --env=prod

echo "[TOMSOL] Migrations..."
php bin/console doctrine:migrations:migrate \
    --no-interaction \
    --env=prod

echo "[TOMSOL] Starting php-fpm..."
php-fpm -D

echo "[TOMSOL] Starting nginx..."
nginx -g "daemon off;"
