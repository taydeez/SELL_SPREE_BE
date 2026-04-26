FROM php:8.3-cli-alpine

# -------------------------
# System dependencies
# -------------------------
RUN apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    postgresql-dev \
    mariadb-dev \
    zlib-dev \
    autoconf \
    gcc \
    g++ \
    make \
    openssl-dev \
    cyrus-sasl-dev \
    snappy-dev \
    supervisor

# -------------------------
# PHP extensions
# -------------------------
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    bcmath \
    intl \
    zip \
    opcache \
    pcntl \
    posix \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && pecl install mongodb \
 && docker-php-ext-enable mongodb \
 && apk del gcc g++ make autoconf

# -------------------------
# Composer
# -------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

WORKDIR /var/www/html

# -------------------------
# Copy FULL application FIRST
# -------------------------
COPY . .

# -------------------------
# Install PHP deps (scripts allowed)
# -------------------------
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# -------------------------
# Laravel runtime prep
# -------------------------
RUN rm -f bootstrap/cache/*.php \
 && mkdir -p storage/framework/cache \
            storage/framework/sessions \
            storage/framework/views \
            storage/logs \
            bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# -------------------------
# Supervisord config
# -------------------------
#COPY docker/queue/supervisord.conf /etc/supervisor/conf.d/queue.conf
#
#CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/queue.conf"]
