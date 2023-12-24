#!/bin/bash
set -e

composer install --no-interaction --optimize-autoloader

mkdir -p /app/var/cache/dev
mkdir -p /app/var/log
chmod -R a+rw /app/var
chown www-data:www-data -R /app/var

printenv > /etc/environment

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
