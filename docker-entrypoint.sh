#!/bin/bash
set -e

# Ensure Laravel storage directories exist and are writable
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/storage/logs

# Only chown if running as root (production); in dev we run as appuser
if [ "$(id -u)" = "0" ]; then
    chown -R www-data:www-data /var/www/html/storage
fi

exec "$@"
