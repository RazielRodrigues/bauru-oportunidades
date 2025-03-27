FROM node:18

WORKDIR /var/www/html/project

ADD ZscalerRootCertificate-2048-SHA256.crt /usr/local/share/ca-certificates/ZscalerRootCertificate-2048-SHA256.crt
RUN update-ca-certificates

RUN npm config set cafile /usr/local/share/ca-certificates/ZscalerRootCertificate-2048-SHA256.crt

CMD [ "node" ]
