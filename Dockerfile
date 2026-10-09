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
# Stage 3: Production PHP 8.3 + Nginx container
# ==========================================
FROM richarvey/nginx-php-fpm:3.1.6
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy production vendor from Stage 1
COPY --from=composer-builder /app/vendor ./vendor

# Copy compiled Vite assets from Stage 2
COPY --from=node-builder /app/public/build ./public/build

# Image & Web Server configuration
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel environment defaults
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# Ensure permissions and executable scripts
RUN chmod +x /var/www/html/scripts/00-laravel-deploy.sh \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["/start.sh"]
