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
    wget \
    && docker-php-ext-install pdo zip bcmath

# Instalacja Oracle Instant Client i oci8
# RUN apt-get update && apt-get install -y libaio1 wget unzip && \
#     mkdir -p /opt/oracle && \
#     cd /opt/oracle && \
#     wget https://download.oracle.com/otn_software/linux/instantclient/instantclient-basiclite-linux.x64-21.13.0.0.0dbru.zip && \
#     unzip instantclient-basiclite-linux.x64-21.13.0.0.0dbru.zip && \
#     rm instantclient-basiclite-linux.x64-21.13.0.0.0dbru.zip && \
#     echo /opt/oracle/instantclient_21_13 > /etc/ld.so.conf.d/oracle-instantclient.conf && \
#     ldconfig

# Rozpakuj i skonfiguruj Instant Client
RUN apt-get update && apt-get install -y libaio1 unzip && \
    cd /opt/oracle && \
    wget https://download.oracle.com/otn_software/linux/instantclient/2380000/instantclient-basic-linux.x64-23.8.0.25.04.zip && \
    wget https://download.oracle.com/otn_software/linux/instantclient/2380000/instantclient-sdk-linux.x64-23.8.0.25.04.zip && \
    unzip instantclient-basic-linux.x64-*.zip && \
    unzip instantclient-sdk-linux.x64-*.zip && \
    rm *.zip && \
    echo /opt/oracle/instantclient_* > /etc/ld.so.conf.d/oracle-instantclient.conf && \
    ldconfig

RUN echo 'instantclient,/opt/oracle/instantclient_23_8' | pecl install oci8 && \
    docker-php-ext-enable oci8

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
