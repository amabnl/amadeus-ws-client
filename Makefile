php_version ?= 8.5
image_name = amadeus-ws-client:${php_version}

SHELL = /bin/sh

build-docker-image:
	docker build -t $(image_name) -f Dockerfile --build-arg PHP_VERSION=$(php_version) .

build-docker-image-once:
	make verify-docker-image-exists || make build-docker-image

build-docker-image-no-cache:
	docker build --no-cache -t $(image_name) -f Dockerfile --build-arg PHP_VERSION=$(php_version) .

verify-docker-image-exists:
	docker image inspect $(image_name) >/dev/null 2>&1

composer-install:
	docker run --rm -ti -v $(shell pwd):/var/www -w /var/www -u $(shell id -u) $(image_name) composer i

phpunit:
	docker run --rm -ti -v $(shell pwd):/var/www -w /var/www -u $(shell id -u) $(image_name) ./vendor/bin/phpunit

test:
	make build-docker-image-once
	make composer-install
	make phpunit
