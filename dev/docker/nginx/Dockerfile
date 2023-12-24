ARG VERSION=1.25.3

FROM nginx:${VERSION}-alpine

RUN apk update && apk upgrade && apk add bash

# Copy nginx config
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy entrypoint script containing build steps
COPY ./docker/nginx/entrypoint.sh /run/entrypoint.sh
RUN chmod +x /run/entrypoint.sh

# setup https with self-signed cert
COPY ./docker/nginx/dhparam.pem /etc/ssl/certs/dhparam.pem
COPY ./docker/nginx/self-signed.crt /etc/ssl/certs/cert.crt
COPY ./docker/nginx/self-signed.key /etc/ssl/private/cert.key

ENTRYPOINT ["/run/entrypoint.sh"]
