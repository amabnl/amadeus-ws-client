FROM php:7.4-fpm-alpine3.16

RUN apk upgrade --no-cache --ignore alpine-baselayout \
    && apk --no-cache add git libzip-dev libxml2-dev libxslt-dev

RUN set -xe \
    && apk add --no-cache --virtual .build-deps \
       $PHPIZE_DEPS \
       coreutils \
    && docker-php-ext-install -j"$(/usr/bin/nproc)" zip soap xsl \
    && pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

COPY ./docker/20-xdebug.ini /tmp/xdebug.ini
RUN cat /tmp/xdebug.ini >> $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini

COPY . /var/www
WORKDIR /var/www