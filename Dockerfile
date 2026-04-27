FROM php:8.3-fpm-alpine

# =========================
# System dependencies
# =========================
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    zlib-dev \
    postgresql-dev \
    mariadb-dev \
    redis \
    nodejs \
    npm \
    $PHPIZE_DEPS

# =========================
# PHP extensions
# =========================
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    bcmath \
    intl \
    zip \
    opcache \
    pcntl \
    posix \
    exif \
    && pecl install redis \
    && docker-php-ext-enable redis

# =========================
# Composer
# =========================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

# =========================
# App directory
# =========================
WORKDIR /var/www/html

# Copy application source
COPY . .

# =========================
# Installing horizon manually here because it
# messes up my local windows composer
# =========================

# =========================
# Install PHP dependencies FIRST
# =========================

RUN composer update

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# Ensure no cached config is baked into image
RUN rm -f bootstrap/cache/config.php

# =========================
# Build frontend AFTER Composer
# =========================
RUN npm install \
    && npm run build

RUN apk del nodejs npm

# =========================
# Laravel runtime directories
# =========================
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache

# =========================
# Nginx config
# =========================
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# =========================
# Supervisor config
# =========================
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# =========================
# PHP-FPM config tweaks
# =========================
RUN sed -i 's|listen = 127.0.0.1:9000|listen = 9000|' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's|;clear_env = no|clear_env = no|' /usr/local/etc/php-fpm.d/www.conf

# =========================
# Expose port
# =========================
EXPOSE 8080

# =========================
# Entrypoint
# =========================
COPY docker/entrypoint.sh /entrypoint.sh
ENTRYPOINT ["sh", "/entrypoint.sh"]

# =========================
# Start services
# =========================
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
