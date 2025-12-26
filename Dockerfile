ARG PHP_VERSION=8.5

FROM php:${PHP_VERSION}-cli-alpine

RUN apk update && apk add --no-cache \
    libxml2-dev \
    libxslt-dev

RUN docker-php-ext-install soap xsl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
