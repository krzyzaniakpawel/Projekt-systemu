# Etap 1: Budowanie assets (JS, CSS) z Node.js
FROM node:18 AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Etap 2: PHP + Laravel + Apache
FROM php:8.2-apache

# Instalacja zależności PHP i narzędzi
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    && docker-php-ext-install pdo pdo_mysql zip bcmath

# Instalacja Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Konfiguracja Apache
RUN a2enmod rewrite
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Ustaw katalog roboczy
WORKDIR /var/www/html

# Skopiuj aplikację
COPY . .

# Skopiuj zbudowane assets z etapu 1
COPY --from=frontend /app/public/build ./public/build

# Instalacja zależności PHP (Laravel)
RUN composer install --no-dev --optimize-autoloader

# Prawa do storage, bootstrap itd.
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Ustaw zmienną środowiskową do środowiska produkcyjnego
ENV APP_ENV=production

EXPOSE 80

CMD ["apache2-foreground"]
