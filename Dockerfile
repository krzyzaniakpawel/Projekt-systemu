# Etap 1: Budowanie assets (JS, CSS) z Node.js
FROM node:18 AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

FROM php:8.2-apache

# Oracle Instant Client Download links
# https://www.oracle.com/database/technologies/instant-client/downloads.html
ENV INSTANTCLIENT "https://download.oracle.com/otn_software/linux/instantclient/2112000/instantclient-basic-linux.x64-21.12.0.0.0dbru.zip"
ENV INSTANTCLIENT_SDK "https://download.oracle.com/otn_software/linux/instantclient/2112000/instantclient-sdk-linux.x64-21.12.0.0.0dbru.zip"
ENV SQLPLUS "https://download.oracle.com/otn_software/linux/instantclient/2112000/instantclient-sqlplus-linux.x64-21.12.0.0.0dbru.zip"

# Oracle Instant Client ENV Variables
ENV LD_LIBRARY_PATH "/opt/oracle/instantclient"
ENV PATH "$PATH:$LD_LIBRARY_PATH"

# Install Oracle Instant Client Libraries
# https://docs.oracle.com/en/database/oracle/oracle-database/21/lacli/install-instant-client-using-zip.html
RUN apt-get update && apt-get install -y unzip libaio1
RUN cd /tmp \ 
    && curl -L $INSTANTCLIENT -O \
    && unzip /tmp/instantclient*.zip -d /opt/oracle/ \
    && rm /tmp/instantclient*.zip \
    && mv /opt/oracle/instantclient_* /opt/oracle/instantclient \
    && echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracleinstantclient.conf \
    && ldconfig
RUN cd /tmp \
    && curl -L $INSTANTCLIENT_SDK -O \
    && unzip /tmp/instantclient-sdk*.zip -d /opt/oracle/ \
    && rm /tmp/instantclient*.zip \
    && mv /opt/oracle/instantclient_*/* /opt/oracle/instantclient
RUN cd /tmp \
    && curl -L $SQLPLUS -O \
    && unzip /tmp/instantclient-sqlplus*.zip -d /opt/oracle/ \
    && rm /tmp/instantclient*.zip \
    && mv /opt/oracle/instantclient_*/* /opt/oracle/instantclient
RUN rmdir /opt/oracle/instantclient_*

# Add custom PHP extensions/libraries
# Install OCI8 Extension
# https://pecl.php.net/package/oci8
RUN apt-get update && apt-get install -y \
        libfreetype-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) iconv gettext gd \
    && echo 'instantclient,/opt/oracle/instantclient' | pecl install oci8 \
    && docker-php-ext-enable oci8

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
RUN chown -R www-data:www-data /var/www/html   

# Ustaw zmienną środowiskową do środowiska produkcyjnego
ENV APP_ENV=production

EXPOSE 80

CMD ["apache2-foreground"]