SHELL := /bin/bash

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$|(^#--)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m %-43s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m #-- /[33m/'

.PHONY: help
.DEFAULT_GOAL := help

# --------------------- Makefile symfony ---------------------------- #

#-- db
db-clean: ## clean the db
	symfony console doctrine:database:drop --if-exists -n --force
	symfony console doctrine:database:create --if-not-exists -n
	symfony console doctrine:migrations:migrate -n

db-init: ## reset the database
	make db-clean
	symfony console doctrine:fixtures:load -n
