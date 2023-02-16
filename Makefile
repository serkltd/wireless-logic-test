PHP_SERVICE := php

build:
	@docker-compose up -d
	@make -s composer

composer:
	@docker-compose exec $(PHP_SERVICE) composer install

run:
	@docker-compose exec $(PHP_SERVICE) symfony console app:scrape:acme

test:
	@docker-compose exec $(PHP_SERVICE) bin/phpunit

analyse:
	@docker-compose exec $(PHP_SERVICE) ./vendor/bin/phpcs --report=full -w --standard=PSR12 ./src
	@docker-compose exec $(PHP_SERVICE) ./vendor/bin/phpstan analyse ./src ./tests

fix:
	@docker-compose exec $(PHP_SERVICE) ./vendor/bin/phpcbf

down:
	@docker-compose down --volumes

clean:
	@docker system prune --volumes --force

all:
	@make -s build
	@make -s composer
	@make -s run
	@make -s test
	@make -s analyse
	@make -s fix
	@make -s down
	@make -s clean
