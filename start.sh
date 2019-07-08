#!/usr/bin/env bash
docker-compose up -d
docker-compose exec php composer install
sleep 600
docker-compose exec php bin/console doctrine:schema:update --force
docker-compose exec php bin/console doctrine:schema:validate
docker-compose exec php bin/console doctrine:fixtures:load -n
