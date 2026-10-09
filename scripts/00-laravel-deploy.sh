#!/usr/bin/env bash
set -e

echo "==> Running Composer Install (production)..."
composer install --no-dev --optimize-autoloader --no-interaction --working-dir=/var/www/html

echo "==> Caching Laravel Configuration..."
php artisan config:cache

echo "==> Caching Laravel Routes..."
php artisan route:cache

echo "==> Caching Laravel Views..."
php artisan view:cache

echo "==> Running Database Migrations..."
php artisan migrate --force

echo "==> Laravel deployment preparation complete!"
