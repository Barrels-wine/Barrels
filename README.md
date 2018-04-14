MyCellar
========

## Pre-requisites

- docker

## Installation

This project uses docker to run.

First you need to modify `app/config/parameters.yml` to set relevant database configuration parameters.

Install python requirements: `pip install -r requirements.txt`.

Then just run `fab local.start`.

## Commands

Several fabric commands are available :

  - `fab cache_clear`: Clear cache of the application
  - `fab cs_fix`: Fix coding standards in code
  - `fab fixtures`: Import fixtures into database
  - `fab install`: Install application (composer, assets)
  - `fab logs`: Show logs of infrastructure
  - `fab migrate_database`: Update database schema
  - `fab restart_service`: Restart a single service
  - `fab ssh`: Ssh into the php container
  - `fab start`: Be sure that everything is started and installed
  - `fab stop`: Stop the infrastructure
  - `fab up`: Ensure infrastructure is sync and running


