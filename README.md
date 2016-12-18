MyCellar
========

# Pre-requisites

- docker

# Installation

This project uses docker to run.

First you need to configure database parameters :

- Copy dist env file with `cp .env.dist .env`
- Then modify `.env` and `app/config/parameters.yml` to set relevant database configuration parameters.

Make sure you have docker installed then launch `docker-compose build` and `docker-compose up -d`.

# Create and update database schema

Go into application container by running `docker-compose exec php bash` then launch `sf doctrine:database:create` and `sf doctrine:schema:update --force`.

# Loading fixtures

Go into application container by running `docker-compose exec php bash` then launch `sf hautelook:fixtures:load`.

Fixtures files are located in `@AppBundle/DataFixtures`.

