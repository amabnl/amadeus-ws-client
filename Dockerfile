FROM php:8.3-apache

# Requirements
RUN apt-get update \
    && apt-get install -y libxslt1-dev libzip-dev unzip git curl

# PHP extensions
RUN docker-php-ext-install soap
RUN docker-php-ext-install xsl

# Add the application
ADD . /var/www
WORKDIR /var/www

RUN git config --system --add safe.directory /var/www

# Install composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer

# Install dependencies
RUN composer i
