#!/usr/bin/env bash

echo "==> Preparing Laravel for production..."

# Create storage symlink if not already present
php artisan storage:link --no-interaction || true

# Cache configuration, routes, and views if APP_KEY is present
if [ -n "$APP_KEY" ]; then
    echo "==> Caching Laravel Configuration..."
    php artisan config:cache || true

    echo "==> Caching Laravel Routes..."
    php artisan route:cache || true

    echo "==> Caching Laravel Views..."
    php artisan view:cache || true
else
    echo "==> [Notice] APP_KEY is not set yet. Skipping artisan cache commands."
fi

# Run database migrations safely (do not crash container if DB credentials are not yet populated)
echo "==> Running Database Migrations..."
php artisan migrate --force || echo "==> [Warning] Database migration failed or database unreachable. Please verify your DB_* environment variables in the Render dashboard."

echo "==> Laravel deployment preparation complete!"

