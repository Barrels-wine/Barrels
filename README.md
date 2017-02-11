MyCellar
========

## Pre-requisites

- docker

## Installation

This project uses docker to run.

First you need to modify `app/config/parameters.yml` to set relevant database configuration parameters.

Install python requirements: `pip install -r requirements.txt`.

Then just run `fab local.infrastructure.up` and `fab local.app.deploy`.

## Commands

Several fabric commands are available :

- `fab local.app.deploy` : Deploy app (composer install, remove and warmup cache, install assets) for specified environment
- `fab local.app.clear_cache` : Remove cache for specified environment
- `fab local.database.generate` : Drop and recreate database then load fixtures if specified
- `fab local.database.populate` : Load fixtures
- `fab local.database.import_csv` : Import data from csv file, use option purge to truncate the wine and bottle tables before. You can specify the csv file path (in csv format) and the mapping file path (in yaml format).
- `fab local.infrastructure.build` : Build project using docker-compose
- `fab local.infrastructure.up` : Build then start the project using docker-compose
- `fab local.infrastructure.reboot` : Stop the project then start it again
- `fab local.infrastructure.stop` : Stop the project
- `fab local.infrastructure.clean` : Remove the stopped service containers
- `fab local.infrastructure.ps` : List active service containers
- `fab local.infrastructure.logs` : Print logs for specified containers or all of them



