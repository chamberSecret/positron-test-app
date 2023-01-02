init: docker-down docker-pull docker-build docker-up app-composer-install app-composer-update app-migrations
up: docker-up
down: docker-down
restart: down up

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build --pull

app-composer-update:
	docker-compose run --rm php-client composer self-update

app-composer-install:
	docker-compose run --rm php-client php -d memory_limit=-1 /bin/composer install --ignore-platform-reqs

app-parse:
	docker-compose run --rm php-client bin/console book:parse

app-migrations:
	docker-compose run --rm php-client bin/console d:m:m --no-interaction

app-fixtures:
	docker-compose run --rm php-client bin/console doctrine:fixtures:load