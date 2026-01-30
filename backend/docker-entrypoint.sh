#!/bin/sh
cd /var/www/html
[ -f .env ] || cp .env.example .env
php artisan key:generate --force
exec "$@"#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
  else
    echo ".env.example not found; creating empty .env"
    touch .env
  fi
fi

php artisan key:generate --force || true

exec "$@"