#first-init: rm-docker-compose docker-create-network-cpa build up env composer-install genrsa migration

up:
	docker-compose up -d --remove-orphans

down:
	docker-compose down

build:
	docker-compose build

down-clear:
	docker-compose down -v --remove-orphans

console-in:
	docker-compose exec php-fpm bash

env:
	docker-compose exec php-fpm cp -n .env.local.example .env.local
	docker-compose exec php-fpm cp -n .env.local.example .env.test.local

composer-install:
	docker-compose exec php-fpm composer install

migration:
	docker-compose exec php-fpm php bin/console d:m:m --no-interaction

new-migration:
	docker-compose exec php-fpm php bin/console d:m:diff

#fixture:
	#docker-compose exec php-fpm php bin/console d:f:l --no-interaction --purge-with-truncate

#recreate:
	#docker-compose exec php-fpm php bin/console d:d:d --force
	#docker-compose exec php-fpm php bin/console d:d:c
	#docker-compose exec postgres psql -d limonad -f /docker-entrypoint-initdb.d/create_extension.sql
	#docker-compose exec php-fpm php bin/console d:m:m --no-interaction
	#docker-compose exec php-fpm php bin/console d:f:l --no-interaction

#rm-docker-compose:
	#cp docker-compose.yaml.dist docker-compose.yaml

#psalm:
	#docker-compose exec php-fpm ./vendor/bin/psalm

test-clean-output:
	docker-compose exec php-fpm php bin/codecept clean

perm:
	sudo chown -R ${USER}:${USER} var
	sudo chown -R ${USER}:${USER} vendor
	sudo chown -R ${USER}:${USER} tests

ps:
	docker-compose ps