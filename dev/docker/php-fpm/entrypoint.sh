#!/bin/bash
set -e

composer install --no-interaction --optimize-autoloader --no-scripts

mkdir -p /app/var/cache/prod
chmod -R a+rw /app/var/cache
chown www-data:www-data -R /app/var/cache

#php bin/console cache:clear
#php bin/console assets:install

printenv > /etc/environment
mkdir -p /app/var/log
chmod -R a+rw /app/var
chown www-data:www-data -R /app/var

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
