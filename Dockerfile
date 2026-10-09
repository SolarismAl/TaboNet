# ==========================================
# Stage 1: Install Composer Dependencies
# ==========================================
FROM composer:2 AS composer-builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev

# ==========================================
# Stage 2: Build frontend assets with Node 22
# ==========================================
FROM node:22-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
# Copy vendor from Stage 1 so Vite/Tailwind can resolve Livewire Flux CSS
COPY --from=composer-builder /app/vendor ./vendor
RUN npm run build

# ==========================================
# Stage 3: Production PHP 8.4 + Nginx container
# ==========================================
FROM serversideup/php:8.4-fpm-nginx

USER root

# Install required PHP extensions for Laravel & TiDB MySQL
RUN install-php-extensions pdo_mysql bcmath intl zip opcache

# Allow adjusting Nginx port if Render specifies a custom PORT
RUN chmod -R 777 /etc/nginx/conf.d /etc/nginx/sites-available /etc/nginx/sites-enabled 2>/dev/null || true

# Set Nginx document root to Laravel public folder & default environment
ENV NGINX_WEBROOT /var/www/html/public
ENV PHP_OPCACHE_ENABLE 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

WORKDIR /var/www/html

# Copy application files
COPY --chown=www-data:www-data . .

# Copy production vendor from Stage 1
COPY --chown=www-data:www-data --from=composer-builder /app/vendor ./vendor

# Copy compiled Vite assets from Stage 2
COPY --chown=www-data:www-data --from=node-builder /app/public/build ./public/build

# Copy deployment/startup script to S6 entrypoint directory
COPY --chmod=755 scripts/00-laravel-deploy.sh /etc/entrypoint.d/00-laravel-deploy.sh

# Ensure storage and bootstrap permissions
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 8080
