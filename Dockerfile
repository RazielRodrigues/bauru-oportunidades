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
EXPOSE 80
VOLUME ./app:/var/www/symfony_docker
VOLUME ./nginx/default.conf:/etc/nginx/conf.d/default.conf

# Add custom Nginx configuration
RUN echo "\
server {\n\
    listen 80;\n\
    index index.php;\n\
    server_name localhost;\n\
    root /var/www/symfony_docker/public;\n\
    error_log /var/log/nginx/project_error.log;\n\
    access_log /var/log/nginx/project_access.log;\n\
\n\
    location / {\n\
        try_files \$uri /index.php\$is_args\$args;\n\
    }\n\
\n\
    location ~ ^/index\\.php(/|\$) {\n\
        fastcgi_pass php:9000;\n\
        fastcgi_split_path_info ^(.+\\.php)(/.*)\$;\n\
        include fastcgi_params;\n\
\n\
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;\n\
        fastcgi_param DOCUMENT_ROOT \$realpath_root;\n\
\n\
        fastcgi_buffer_size 128k;\n\
        fastcgi_buffers 4 256k;\n\
        fastcgi_busy_buffers_size 256k;\n\
\n\
        internal;\n\
    }\n\
\n\
    location ~ \\.php\$ {\n\
        return 404;\n\
    }\n\
}\n\
" > /etc/nginx/conf.d/default.conf
