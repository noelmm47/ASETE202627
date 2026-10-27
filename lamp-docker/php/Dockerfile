FROM php:8.3.36-fpm-alpine

# Instalar las extensiones PHP necesarias
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli

WORKDIR /var/www/HTML

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN echo "display_errors = On" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/custom.ini