#!/bin/bash
set -e

rm -rf /app/dev/var

cd ../.
composer install
cd dev

php bin/console cache:clear
php bin/console assets:install --symlink

mkdir -p /app/dev/var/cache/dev
mkdir -p /app/dev/var/log
mkdir -p /app/dev/var/log/php_error
chmod -R a+rw /app/dev/var
chown www-data:www-data -R /app/dev/var

printenv > /etc/environment

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
