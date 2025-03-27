start:
	docker compose up -d --build
	docker exec -it bauru_jobs_php_container composer install

db:
	docker exec -it bauru_jobs_php_container php bin/console doctrine:schema:drop --full-database --force
	docker exec -it bauru_jobs_php_container php bin/console make:migration
	docker exec -it bauru_jobs_php_container php bin/console doctrine:migrations:migrate -n
	docker exec -it bauru_jobs_php_container php bin/console doctrine:fixtures:load -n

php:
	docker exec -it bauru_jobs_php_container bash

node:
	docker exec -it bauru_jobs_node_container bash

mariadb:
	docker exec -it bauru_jobs_mariadb_container bash