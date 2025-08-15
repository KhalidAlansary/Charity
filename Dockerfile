FROM docker.io/php:fpm

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

RUN mkdir /var/www/logs && chown www-data:www-data /var/www/logs
