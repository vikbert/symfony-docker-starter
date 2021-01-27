SHELL := /bin/bash

help:
	# shellcheck disable=SC2046
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$|(^#--)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m %-43s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m #-- /[33m/'

.PHONY: help
.DEFAULT_GOAL := help

#-- db
db-clean: ## clean the db
	docker-compose exec php bin/console doctrine:database:drop --if-exists -n --force
	docker-compose exec php bin/console doctrine:database:create --if-not-exists -n

db-migrate: ## doctrine migrate
	docker-compose exec php bin/console doctrine:migrations:migrate -n

#-- docker
docker-clean: ## clean up all docker resource
	docker-compose stop
	docker container prune -f
	docker volume prune -f
	docker network prune -f
	docker-compose up -d
