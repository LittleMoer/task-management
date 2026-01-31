#!/bin/sh
set -e

cd /var/www/html

# Ensure .env exists
if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
  else
    touch .env
  fi
fi

# Generate app key
php artisan key:generate --force || true

# Run migrations
php artisan migrate --force || true

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port=8000