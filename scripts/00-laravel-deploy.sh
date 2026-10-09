#!/usr/bin/env bash

cd /var/www/html

echo "==> Preparing TaboNet for production..."

# If Render specifies a custom PORT other than 8080, dynamically adjust Nginx
if [ -n "$PORT" ] && [ "$PORT" != "8080" ]; then
    echo "==> Adjusting Nginx listen port to $PORT..."
    find /etc/nginx -name "*.conf" -exec sed -i "s/8080/$PORT/g" {} + 2>/dev/null || true
fi

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

echo "==> TaboNet deployment preparation complete!"
