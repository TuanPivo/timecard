## Install project
git clone https://github.com/TuanPivo/timecard.git

copy file env.example to env

run composer install

run php artisan generate:key

config db
db name : time-card

run php artisan migrate

run php artisan db:seed --class=HolidaysTableSeeder
run php artisan db:seed --class=UserSeeder

# Installation
*The following docker compose commands will be executed in the docker directory.*

```bash
cd <path-to-project>/docker
sh ./up.sh install
```
The command above executes the following commands:
```bash
docker compose build
docker compose up -d
docker compose exec admin composer install
docker compose exec admin cp .env.example .env
docker compose exec admin php artisan key:generate
docker compose exec admin php artisan storage:link
docker compose exec admin php artisan migrate
```

The following 3 containers will start up

- **kerkel-admin**(php:8.3.9-apache)  
- **kerkel-pma**(mysql:latest)  
- **kerkel-mysql**(phpmyadmin:latest)  

Supported make commands:

```properties
Usage:
  sh ./up.sh <target>

Targets:
  install, i          Build and bring up the Docker containers, install dependencies, configure the environment, and run initial migrations.
  up                  Bring up the Docker containers in detached mode.
  build               Build or rebuild the Docker containers.
  stop                Stop all running Docker containers.
  down                Stop and remove Docker containers, networks, volumes, and images created by 'up'.
  restart             Restart all Docker containers.
  ps                  List containers.
  app                 Access the application's bash shell.
  front               Access the frontend container's bash shell.
  migrate, m          Run the database migrations.
  fresh               Drop all tables and re-run all migrations with seeding.
  seed                Run the database seeders.
  rollback            Rollback all migrations and then refresh them.
  test                Run the application tests.
  optimize            Optimize the framework for better performance.
  optimize_clear, oc  Clear all cached data and reset the optimization.
  mysql               Access the MySQL database bash shell.
```

