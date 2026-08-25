#!/bin/sh
set -e

chown -R www-data:www-data /var/www/html/var
chmod -R 775 /var/www/html/var

exec "$@"