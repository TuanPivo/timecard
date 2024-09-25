#!/bin/bash
[ ! -f .env ] || export $(grep -v '^#' .env | xargs)

install() {
    build
    up
    docker compose exec ${CONTAINER_APP} composer install
    docker compose exec ${CONTAINER_APP} cp .env.example .env
    docker compose exec ${CONTAINER_APP} php artisan key:generate
    docker compose exec ${CONTAINER_APP} php artisan storage:link
    migrate
}

up() {
    docker compose up -d
}

build() {
    docker compose build
}

stop() {
    docker compose stop
}

down() {
    docker compose down --remove-orphans
}

restart() {
    down
    up
}

ps() {
    docker compose ps
}

app() {
    docker compose exec ${CONTAINER_APP} bash
}

migrate() {
    docker compose exec ${CONTAINER_APP} php artisan migrate
}

fresh() {
    docker compose exec ${CONTAINER_APP} php artisan migrate:fresh --seed
}

seed() {
    docker compose exec ${CONTAINER_APP} php artisan db:seed
}

rollback() {
    docker compose exec ${CONTAINER_APP} php artisan migrate:fresh
    docker compose exec ${CONTAINER_APP} php artisan migrate:refresh
}

test() {
    docker compose exec ${CONTAINER_APP} php artisan test
}

optimize() {
    docker compose exec ${CONTAINER_APP} php artisan optimize
}

optimize_clear() {
    docker compose exec ${CONTAINER_APP} php artisan optimize:clear
}

mysql() {
    docker compose exec ${CONTAINER_DB} bash
}

case "$1" in
    install) install ;;
    i) install ;;
    up) up ;;
    build) build ;;
    stop) stop ;;
    down) down ;;
    restart) restart ;;
    ps) ps ;;
    app) app ;;
    front) front ;;
    migrate) migrate ;;
    m) migrate ;;
    fresh) fresh ;;
    seed) seed ;;
    rollback) rollback ;;
    test) test ;;
    optimize) optimize ;;
    optimize_clear) optimize_clear ;;
    oc) optimize_clear ;;
    mysql) mysql ;;
    *) echo "Usage: $0 {install|i|up|build|stop|down|restart|ps|app|front|migrate|m|fresh|seed|rollback|test|optimize|optimize_clear|oc|mysql}"
        echo
        echo "Commands:"
        echo "  install, i          Build and bring up the Docker containers, install dependencies, configure the environment, and run initial migrations."
        echo "  up                  Bring up the Docker containers in detached mode."
        echo "  build               Build or rebuild the Docker containers."
        echo "  stop                Stop all running Docker containers."
        echo "  down                Stop and remove Docker containers, networks, volumes, and images created by 'up'."
        echo "  restart             Restart all Docker containers."
        echo "  ps                  List containers."
        echo "  app                 Access the application's bash shell."
        echo "  front               Access the frontend container's bash shell."
        echo "  migrate, m          Run the database migrations."
        echo "  fresh               Drop all tables and re-run all migrations with seeding."
        echo "  seed                Run the database seeders."
        echo "  rollback            Rollback all migrations and then refresh them."
        echo "  test                Run the application tests."
        echo "  optimize            Optimize the framework for better performance."
        echo "  optimize_clear, oc  Clear all cached data and reset the optimization."
        echo "  mysql               Access the MySQL database bash shell."
        echo
        echo "For more information, see the project's documentation."
        ;;
esac
