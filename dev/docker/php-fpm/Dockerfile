ARG VERSION=8.1

FROM php:${VERSION}-fpm-bullseye

# Install packages
RUN apt-get update \
    && apt-get install -y \
    libghc-zlib-dev \
    libzip-dev \
    supervisor \
    zip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install opcache zip
RUN pecl install xdebug && docker-php-ext-enable xdebug

## Copy php default configuration
COPY ./docker/php-fpm/default.ini /usr/local/etc/php/conf.d/default.ini
COPY ./docker/php-fpm/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

## Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY ./docker/php-fpm/entrypoint.sh /run/entrypoint.sh
RUN chmod +x /run/entrypoint.sh
ENTRYPOINT ["/run/entrypoint.sh"]
