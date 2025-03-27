FROM php:8.2.7-apache-bullseye
RUN apt update
# SSL-Zertifikat kopieren
COPY ./config/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
COPY ./config/ssl-apache.crt /etc/ssl/certs/
COPY ./config/ssl-apache.key /etc/ssl/private/

RUN a2enmod proxy_fcgi rewrite ssl
RUN a2enmod ssl && a2ensite default-ssl && service apache2 restart && service apache2 reload
