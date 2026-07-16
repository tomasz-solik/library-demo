#!/bin/sh

set -e

echo "PHP modules:"
php -m | grep -E "pdo|pgsql"

#echo "Running migrations..."
#
#php bin/console doctrine:migrations:migrate \
#    --no-interaction || true

php-fpm -D

nginx -g "daemon off;"
