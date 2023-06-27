include .env

up:
	docker-compose up -d
install:
	docker-compose exec --user="${UID}:${GID}" php-fpm-gd sh -c 'composer create-project symfony/skeleton:"6.3.*" .'
	docker-compose exec php-fpm-gd sh -c 'cd /var/www/app && cp ../symfony.env .env && chmod 777 .env'
	docker-compose exec --user="${UID}:${GID}" php-fpm-gd sh -c 'bin/console doctrine:migrations:migrate --no-interaction'

worker:
	docker-compose exec --user="${UID}:${GID}" php-fpm-gd sh -c 'bin/console app:sync-images -d'

deps:
	docker-compose exec --user="${UID}:${GID}" php-fpm-gd sh -c \
	'composer require symfony/twig-pack && \
	composer require symfony/orm-pack && \
	composer require symfony/serializer && \
    composer require symfony/property-access && \
  	composer require symfony/filesystem && \
    composer require symfony/finder && \
    composer require symfony/console && \
    composer require symfony/monolog-bundle && \
    composer require sensio/framework-extra-bundle && \
    composer require symfony/validator && \
    composer require symfony/mime'
down:
	docker-compose down
rebuild:
	docker-compose up -d --build --force-recreate --no-deps
cli:
	docker-compose exec --user="${UID}:${GID}" php-fpm-gd sh
root:
	docker-compose exec php-fpm-gd sh
test_db:
	docker-compose exec --user="${UID}:${GID}" php-fpm-gd bin/console dbal:run-sql \
	"select 'succesfully connected!' as result;"
