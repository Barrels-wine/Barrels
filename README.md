MyCellar
========

## Pre-requisites

- docker

## Installation

This project uses docker to run.

If running the project for the first time you will need to modify your `/etc/hosts` file to include the following line :

```
127.0.0.1 api.mycellar.test
```

You can now install python requirements: `pip install -r requirements.txt`.

Then just run `fab start`.

The project is available at `http://api.mycellar.test`. You can use an api client (eg. Postman) to play with it.

## Commands

Several fabric commands are available :

  - `fab cache_clear`:       Clear cache of the application
  - `fab clean`:             Clean the infrastructure, remove all data
  - `fab clean_start`:       Start everything from fresh
  - `fab cs_fix`:            Fix coding standards in code
  - `fab diff_migration`:    Generate a migration by comparing the current database to the mapping inform...
  - `fab generate_database`: Drop and recreate database with updated schema then load fixtures if specifi...
  - `fab import_csv`:        Import data from csv file, use option purge to truncate the wine and bottle ...
  - `fab init_database`:     (Re)Create an empty database
  - `fab install`:           Install application (composer, assets)
  - `fab logs`:              Show logs for all container
  - `fab migrate`:           Apply available database migrations
  - `fab populate`:          Import fixtures into database
  - `fab reboot`:            Reboot the infrastructure
  - `fab restart_service`:   Restart a single service
  - `fab ssh`:               Ssh into the application container
  - `fab start`:             Ensure everything is started and installed
  - `fab stop`:              Stop the infrastructure
  - `fab up`:                Ensure infrastructure is synced and running
