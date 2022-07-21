up:
	docker-compose up -d --remove-orphans

down:
	docker-compose down

restart: down up

build:
	docker-compose build

down-clear:
	docker-compose down -v --remove-orphans

console-in:
	docker-compose exec php-fpm bash

env:
	cp -n docker-compose.yaml.dist docker-compose.yaml
	cp -n .env.local.example .env.local
	cp -n .env.local.example .env.test.local

composer-install:
	docker-compose exec -T php-fpm composer install

migration:
	docker-compose exec -T php-fpm php bin/console d:m:m --no-interaction

new-migration:
	docker-compose exec php-fpm php bin/console d:m:diff

test:
	docker-compose exec -T php-fpm php bin/console doctrine:migrations:migrate --env test --no-interaction
	docker-compose exec -T php-fpm php vendor/codeception/codeception/codecept run

test-clean-output:
	docker-compose exec -T php-fpm php vendor/codeception/codeception/codecept clean

rm-cache:
	docker-compose exec -T php-fpm rm -r var/log var/cache vendor/

perm:
	sudo chown -R ${USER}:${USER} var
	sudo chown -R ${USER}:${USER} vendor
	sudo chown -R ${USER}:${USER} tests
	sudo chmod 777 var vendor tests

ps:
	docker-compose ps