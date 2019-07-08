# Sample Symfony 4 project

A very simple project for testing Symfony 4 with Docker installation process.

Based on https://github.com/eko/docker-symfony.

## If you clone this repo

To run the project locally please use included `start.sh` shell script or you have to use the following commands:

```
docker-compose up -d
docker-compose run php composer install
# Please give up to 10 minutes to docker to install and configure time zone data for MariaDB.
docker-compose exec php bin/console doctrine:schema:update --force
docker-compose exec php bin/console doctrine:schema:validate
docker-compose exec php bin/console doctrine:fixtures:load -n
```

and add `127.0.0.1 btb.dev.fb` to your hosts file.
