
FROM php:8.2.8-fpm

# Use the default production configuration (mv command is executed in container, $PHP_INI_DIR is an Env-Variable existing in the image php:8.1.0-fpm)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
WORKDIR /var/www/html/project

#Add the Zscaler Certificate to the container
ADD ZscalerRootCertificate-2048-SHA256.crt /usr/local/share/ca-certificates/ZscalerRootCertificate-2048-SHA256.crt
RUN update-ca-certificates

RUN pecl channel-update pecl.php.net

RUN apt-get update && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
     && docker-php-ext-install intl opcache pdo pdo_mysql \
     && pecl install apcu \
     && docker-php-ext-enable apcu \
     && docker-php-ext-configure zip \
     && docker-php-ext-install zip \
     && pecl install xdebug \
     && docker-php-ext-enable xdebug \
     && apt-get install -y wget \
     && apt-get install -y nano

#  die UID und GID des Host-Benutzers im Container anlegen, um sie im Container zu benutzen, damit von der IDE aus die Dateien im Container editiert werden können
ARG USERNAME=razielrodrigues
ARG USER_UID=1000
ARG USER_GID=$USER_UID

RUN groupadd --gid $USER_GID $USERNAME \
     && useradd --uid $USER_UID --gid $USER_GID -m $USERNAME \
     && apt-get install -y sudo \
     && echo $USERNAME ALL=\(root\) NOPASSWD:ALL > /etc/sudoers.d/$USERNAME \
     && chmod 0440 /etc/sudoers.d/$USERNAME

# git und git-config
RUN apt-get update && apt-get install -y git

#allow super user for composer at all time like in docker-containers
ENV COMPOSER_ALLOW_SUPERUSER = 1

#install composer and symfony cli
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#old way to install symfony cli
#RUN curl -sS https://get.symfony.com/cli/installer  | bash

#new way to install symfony cli
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
RUN sudo apt install symfony-cli

# [Optional] Set the default user. Omit if you want to keep the default as root.
# hier erforderlich, da sonst die git-config (email ..) im Container nur für root gesetzt wird. Aber hier soll es für den Host-Benutzer your_user gesetzt werden.
# USER $USERNAME
# RUN git config --global user.email "razielx3@live.com" \
#      && git config --global user.name "razielrodrigues"