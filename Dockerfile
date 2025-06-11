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

ENV LD_LIBRARY_PATH="/opt/oracle/instantclient_19_10/"
ENV ORACLE_HOME="/opt/oracle/instantclient_19_10/"
ENV OCI_HOME="/opt/oracle/instantclient_19_10/"
ENV OCI_LIB_DIR="/opt/oracle/instantclient_19_10/"
ENV OCI_INCLUDE_DIR="/opt/oracle/instantclient_19_10/sdk/include"
ENV OCI_VERSION=19

# Download Oracle
RUN mkdir /opt/oracle \
    && cd /opt/oracle \
    && wget https://download.oracle.com/otn_software/linux/instantclient/191000/instantclient-basic-linux.x64-19.10.0.0.0dbru.zip \
    && wget https://download.oracle.com/otn_software/linux/instantclient/191000/instantclient-sdk-linux.x64-19.10.0.0.0dbru.zip \
    && unzip /opt/oracle/instantclient-basic-linux.x64-19.10.0.0.0dbru.zip -d /opt/oracle \
    && unzip /opt/oracle/instantclient-sdk-linux.x64-19.10.0.0.0dbru.zip -d /opt/oracle \
    && rm -rf /opt/oracle/*.zip \
    && echo /opt/oracle/instantclient_19_10 > /etc/ld.so.conf.d/oracle-instantclient.conf \
    && ldconfig

# Configure Oracle
RUN apt-get update \
    && apt-get install -y \
      php-dev \
      php-pear \
      build-essential \
      libaio1 \
      libaio-dev \
      freetds-dev
RUN pecl channel-update pecl.php.net \
    && echo 'instantclient,/opt/oracle/instantclient_19_10' | pecl install oci8 \
    && echo extension=oci8.so >> /etc/php/8.2/cli/php.ini \
    && echo "extension=oci8.so" >> /etc/php/8.2/mods-available/oci8.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=8000
