include .env

up:
	docker-compose up -d
install:
	docker-compose exec --user="${UID}:${GID}" php-fpm-gd sh -c 'composer create-project symfony/skeleton:"6.3.*" .'
	docker-compose exec --user="${UID}:${GID}" php-fpm-gd sh -c \
	'composer require symfony/twig-pack && composer require symfony/orm-pack'
	docker-compose exec php-fpm-gd sh -c 'cd /var/www/app && cp ../symfony.env .env && chmod 777 .env'
down:
	docker-compose down
rebuild:
	docker-compose up -d --build --force-recreate --no-deps
cli:
	docker-compose exec --user="${UID}:${GID}" php-fpm-gd sh
test_db:
	docker-compose exec --user="${UID}:${GID}" php-fpm-gd bin/console dbal:run-sql \
	"select 'succesfully connected!' as result;"
