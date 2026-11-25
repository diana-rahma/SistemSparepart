
# Tahap 1: Build frontend React dan Vite
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# Tahap 2: Install dependency Laravel
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts \
    --no-progress


# Tahap 3: Image aplikasi Laravel dengan FrankenPHP
FROM dunglas/frankenphp:1-php8.3-bookworm

WORKDIR /app

# Menyiapkan sertifikat CA untuk koneksi TLS
RUN apt-get update \
    && apt-get install -y --no-install-recommends ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Ekstensi PHP yang dibutuhkan aplikasi
RUN install-php-extensions \
    bcmath \
    exif \
    gd \
    intl \
    mbstring \
    opcache \
    pcntl \
    pdo_mysql \
    zip

# Mengaktifkan konfigurasi PHP production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Menyalin source code aplikasi
COPY . .

# Mengambil dependency Composer dari tahap sebelumnya
COPY --from=vendor /app/vendor ./vendor

# Mengambil hasil build frontend
COPY --from=frontend /app/public/build ./public/build

# Menyalin konfigurasi Caddy
COPY Caddyfile /etc/frankenphp/Caddyfile

# Menjalankan package discovery dan mengatur permission
RUN php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache
