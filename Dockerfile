# syntax=docker/dockerfile:1

##############################
# Base: PHP 8.2 + Apache     #
##############################
FROM php:8.2-apache AS base

# System libraries and the PHP extensions the application needs
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu-dev \
        unzip \
        git \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql intl opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer (vendor-dir is private/vendor, configured in composer.json)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Serve the web/ document root and honour web/.htaccess
COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Shared PHP runtime configuration
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini

WORKDIR /var/www/html

##############################
# Development image          #
##############################
FROM base AS dev

# Xdebug; disabled unless XDEBUG_MODE is provided at runtime
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/zz-xdebug.ini

COPY docker/entrypoint-dev.sh /usr/local/bin/entrypoint-dev
RUN chmod +x /usr/local/bin/entrypoint-dev

ENTRYPOINT ["entrypoint-dev"]
CMD ["apache2-foreground"]

##############################
# Frontend assets (Vite)     #
##############################
FROM node:22-slim AS assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY private/assets ./private/assets
RUN npm run build

##############################
# Production image           #
##############################
FROM base AS prod

# Application code (see .dockerignore for what is excluded)
COPY . .

# PHP dependencies without dev tooling, optimised autoloader
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Compiled frontend assets from the assets stage
COPY --from=assets /app/web/assets ./web/assets

# Production opcache and writable runtime directories
COPY docker/php/opcache-prod.ini /usr/local/etc/php/conf.d/zz-opcache.ini
RUN mkdir -p private/temp private/log \
    && chown -R www-data:www-data private/temp private/log web/assets
