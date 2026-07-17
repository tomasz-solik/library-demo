#!/bin/sh

set -e

echo "[TOMSOL]PHP modules:"
php -m | grep -E "pdo|pgsql"

echo "[TOMSOL]Waiting for database..."
php bin/console doctrine:database:create --if-not-exists

echo "[TOMSOL]Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

echo "[TOMSOL]Starting php-fpm..."
php-fpm -D

echo "[TOMSOL]Starting nginx..."

nginx -g "daemon off;"
