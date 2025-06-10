FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    unzip \
    libaio1 \
    wget \
    git \
    gnupg2 \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    curl \
    && docker-php-ext-install pdo mbstring zip xml

RUN mkdir -p /opt/oracle \
    && cd /opt/oracle \
    && wget https://download.oracle.com/otn_software/linux/instantclient/211000/instantclient-basiclite-linux.x64-21.10.0.0.0dbru.zip \
    && unzip instantclient-basiclite-linux.x64-21.10.0.0.0dbru.zip \
    && rm instantclient-basiclite-linux.x64-21.10.0.0.0dbru.zip \
    && ln -s /opt/oracle/instantclient_21_10 /opt/oracle/instantclient \
    && echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracle-instantclient.conf \
    && ldconfig

ENV LD_LIBRARY_PATH="/opt/oracle/instantclient"

RUN echo 'instantclient,/opt/oracle/instantclient' | pecl install oci8 \
    && docker-php-ext-enable oci8

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=8000
