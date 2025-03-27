#!/bin/bash
set -e
docker exec -it bauru_jobs_php composer install
docker exec -it bauru_jobs_php php bin/console doctrine:database:create --if-not-exists
docker exec -it bauru_jobs_php php bin/console doctrine:migrations:migrate --no-interaction
docker exec -it bauru_jobs_php php bin/console doctrine:fixtures:load