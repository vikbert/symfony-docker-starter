SHELL := /bin/bash

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$|(^#--)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m %-43s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m #-- /[33m/'

.PHONY: help
.DEFAULT_GOAL := help

# --------------------- Makefile docker ---------------------------- #


#-- docker
docker-clean: ## clean up all docker resource
	docker-compose stop
	docker container prune -f
	docker volume prune -f
	docker network prune -f
