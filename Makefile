start:
	docker compose up -d --build
	docker exec -it portugal_docs_php_container composer install
	docker exec -it portugal_docs_php_container php bin/console tailwind:build

db:
	docker exec -it portugal_docs_php_container php bin/console doctrine:schema:drop --full-database --force
	docker exec -it portugal_docs_php_container php bin/console make:migration
	docker exec -it portugal_docs_php_container php bin/console doctrine:migrations:migrate -n
	docker exec -it portugal_docs_php_container php bin/console doctrine:fixtures:load -n

php:
	docker exec -it portugal_docs_php_container bash

node:
	docker exec -it portugal_docs_node_container bash

mariadb:
	docker exec -it portugal_docs_mariadb_container bash