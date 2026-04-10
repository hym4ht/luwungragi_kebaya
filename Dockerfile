FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN composer dump-autoload --no-dev --classmap-authoritative \
    && php artisan package:discover --ansi

# --- PERBAIKAN DI SINI ---
FROM node:22-bookworm-slim AS frontend

WORKDIR /app

# Tambahkan ini supaya Vite (devDependencies) mau di-install
ENV NODE_ENV=development 

COPY package.json package-lock.json ./

# Ganti 'npm ci' ke 'npm install' agar lebih fleksibel di server
RUN npm install 

COPY . .

# Build assets
RUN npm run build
# --- AKHIR PERBAIKAN ---

FROM php:8.4-fpm-bookworm

WORKDIR /var/www

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libzip-dev \
        nginx \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        pdo_mysql \
        zip \
    && rm -f /etc/nginx/sites-enabled/default /etc/nginx/conf.d/default.conf \
    && mkdir -p /run/php /var/lib/nginx /var/log/nginx \
    && rm -rf /var/lib/apt/lists/*

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint-app
COPY docker/run.sh /usr/local/bin/docker-run-app

RUN chmod +x /usr/local/bin/docker-entrypoint-app /usr/local/bin/docker-run-app

COPY . /var/www
COPY --from=vendor /app/vendor /var/www/vendor
COPY --from=vendor /app/bootstrap/cache /var/www/bootstrap/cache
COPY --from=frontend /app/public/build /var/www/public/build

RUN mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Jika Laravel kamu belum ada route '/up', healthcheck ini bisa bikin 503. 
# Untuk sementara bisa kamu comment jika masih error 503.
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD curl -fsS http://127.0.0.1/up || exit 1

EXPOSE 80

ENTRYPOINT ["docker-entrypoint-app"]
CMD ["docker-run-app"]