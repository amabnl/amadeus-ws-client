image_name = amadeus-ws-client:build
composer_version = 2.5.1

SHELL = /bin/sh

build-docker-image:
	docker build -t $(image_name) -f docker/Dockerfile .

build-docker-image-once:
	make verify-docker-image-exists || make build-docker-image

build-docker-image-no-cache:
	docker build --no-cache -t $(image_name) -f docker/Dockerfile .

composer-download:
	test -f composer.phar || wget -O composer.phar https://getcomposer.org/download/$(composer_version)/composer.phar

composer-install:
	make composer-download
	test -d ./vendor || docker run --rm -ti -v ~/.composer:/.composer -v $(shell pwd):/var/www -w /var/www -u $(shell id -u) $(image_name) php composer.phar install

composer-update:
	make composer-download
	docker run --rm -ti -v ~/.composer:/.composer -v $(shell pwd):/var/www -w /var/www -u $(shell id -u) $(image_name) php composer.phar update

phpunit:
	docker run --rm -ti -v $(shell pwd):/var/www -w /var/www -u $(shell id -u) $(image_name) vendor/bin/phpunit tests/

test:
	make build-docker-image-once
	make composer-install
	make phpunit

verify-docker-image-exists:
	docker image inspect $(image_name) >/dev/null 2>&1