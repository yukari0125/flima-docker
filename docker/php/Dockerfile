FROM php:8.3-fpm-bookworm

RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libonig-dev libicu-dev \
  && docker-php-ext-install pdo_mysql mbstring zip intl \
  && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
