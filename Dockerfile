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
FROM php:latest AS php_service
EXPOSE 9000
VOLUME ./app:/var/www/symfony_docker

# Base image for Nginx
FROM nginx:stable-alpine AS nginx_service
EXPOSE 80
VOLUME ./app:/var/www/symfony_docker
VOLUME ./nginx/default.conf:/etc/nginx/conf.d/default.conf
