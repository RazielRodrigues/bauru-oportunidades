# Dockerfile

# Base image for MySQL
FROM mysql:8.0 AS mysql_service
CMD ["--default-authentication-plugin=mysql_native_password"]
ENV MYSQL_ROOT_PASSWORD=secret
ENV MYSQL_DATABASE=symfony_docker
ENV MYSQL_USER=symfony
ENV MYSQL_PASSWORD=symfony
EXPOSE 3306
VOLUME ./mysql:/var/lib/mysql

# Base image for PHP
FROM php:7.4-fpm AS php_service
RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 9000
VOLUME ./app:/var/www/symfony_docker

# Base image for Nginx
FROM nginx:stable-alpine AS nginx_service

# Remove the default Nginx configuration
RUN rm /etc/nginx/conf.d/default.conf

# Add custom Nginx configuration
COPY nginx/default.conf /etc/nginx/conf.d/

EXPOSE 80
VOLUME ./app:/var/www/symfony_docker