CONTAINER_NAME = mycompany_api
DOCKER_COMPOSE = docker-compose
DOCKER = docker
DOCKER_PHP = $(DOCKER) exec -it $(CONTAINER_NAME)_www sh -c
SYMFONY = $(DOCKER_PHP) "php bin/console ${ARGS}"
DOCKER_COMPOSER = $(DOCKER) exec -it $(CONTAINER_NAME)_composer sh -c
DOCKER_DATABASE = "$(DOCKER) exec -it $(CONTAINER_NAME)_database sh -c"

##
## ALIAS
## -------
##

cc: cache-clear
cw: cache-warmup
cs: phpcs
stan: phpstan
cbf: phpcbf
quality: phpcs phpcbf
test: test-unit phpstan phpcs
pp: vendor migrations-migrate cache-clear cache-warmup #Post pull command
sf-cmd: symfony-cmd

##
## projet
## -------
##

.DEFAULT_GOAL := help

help: ## Default goal (display the help message)
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-20s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: help

##
## Docker
## -------
##
start: ## Start environnement docker.
start: docker-compose.yml
	$(DOCKER_COMPOSE) up -d --build

init: ## Initialize projet
init:
	make start
	make exec-php ARGS="chmod -R 777 var/cache var/log"

destroy: ## Destroy all containers & network
destroy:
	$(DOCKER_COMPOSE) down

stop: ## Stop all containersv related projet
stop:
	$(DOCKER_COMPOSE) stop

list-containers: ## List container docker
list-containers:
	$(DOCKER_COMPOSE) ps

list-network: ## List all networks on host
list-network:
	$(DOCKER) network ls

inspect-network: ## Inspect current network to list all container ips
inspect-network:
	$(DOCKER) network inspect $(NETWORK_NAME)

erase-all: ## Careful, erase all container, all images
erase-all:
	$(DOCKER) stop $$(docker ps -a -q) && $(DOCKER) rm $$(docker ps -a -q) $(DOCKER) rmi $$(docker images -a -q) -f

exec-php: ## Exec command inside container php. Use argument ARGS
exec-php:
	$(DOCKER_PHP) "${ARGS}"

exec-database: ## Exec command inside container database. Use argument ARGS
exec-database:
	$(DOCKER_DATABASE) "${ARGS}"

connect-php: ## Connect sh to container php
connect-php:
	$(DOCKER) exec -u -it $(CONTAINER_NAME)_www sh

stop-all: ## Stop all containers
stop-all:
	$(DOCKER) stop $$(docker ps -a -q)

##
## Manage dependencies
## -------
##

vendor: ## Install composer dependencies
vendor: ../api/composer.lock
	$(DOCKER_COMPOSER) "composer install"

new-vendor: ## Add dependency or dev dependency. Use argument ARGS (Example : make new-vendor ARGS="security form" or make new-vendor ARGS="profiler --dev"
new-vendor: ../api/composer.json
	$(DOCKER_COMPOSER) "composer require ${ARGS}"

dump-autoload: ## Optimize autoloading and vendor
dump-autoload: ../api/composer.lock
	$(DOCKER_COMPOSER) "composer dump-autoload"

##
## Symfony Command
## -------
##

symfony-cmd: ## Exec command symfony inside php container. Use argument ARGS to define command. Example : make symfony-cmd ARGS="assets:install"
symfony-cmd:
	$(SYMFONY)

cache-clear: ## Clear the cache (by default, the dev env is used, use ARGS argument to change)
cache-clear:
	$(DOCKER_PHP) "php bin/console cache:clear --env=$(or $(ARGS), dev)"

cache-warmup: ## Clear the cache warmup (by default, the dev env is used, use ARGS arguement to change)
cache-warmup:
	$(DOCKER_PHP) "php bin/console cache:warmup --env=$(or $(ARGS), dev)"

migrations-diff: ## Generate diff migrations doctrine
migrations-diff:
	$(DOCKER_PHP) "php bin/console doctrine:migrations:diff"

migrations-exec: ## Execute migrations
migrations-migrate:
	$(DOCKER_PHP) "php bin/console doctrine:migrations:migrate -n"

phpcs: ## Run phpcs
phpcs: ${PATH_PROJECT}/vendor/bin/phpcs
	$(DOCKER_PHP) "vendor/bin/phpcs"

phpstan: ## Run phpstan
phpstan: ${PATH_PROJECT}/vendor/bin/phpstan
	$(DOCKER_PHP) "vendor/bin/phpstan analyze src"

phpcbf: ## Run PHPCBF
phpcbf: ${PATH_PROJECT}/vendor/bin/phpcbf
	$(DOCKER_PHP) vendor/bin/phpcbf

##
## TESTS
## ------
##
test-unit: ## Run phpunit
test-unit: ${PATH_PROJECT}/tests
	$(DOCKER_PHP) "vendor/bin/phpunit $(ARGS)"

coverage: ## Run phpunit with code coverage
coverage: ${PATH_PROJECT}/tests
	make ex
	$(DOCKER_PHP) "XDEBUG_MODE=coverage vendor/bin/phpunit $(ARGS) --coverage-html coverage"
	make dx
