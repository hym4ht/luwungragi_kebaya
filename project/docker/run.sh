#!/bin/sh
set -e

mkdir -p /run/php /var/lib/nginx /var/log/nginx

php-fpm -D

exec nginx -g 'daemon off;'
