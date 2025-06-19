COMPOSE=docker compose -f docker-compose.yaml
EXEC=${COMPOSE} exec -u 1000 php

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install:	## Initialize the project and install dependencies
	chmod +x php-exec-path.sh
	${COMPOSE} up -d --build
	${EXEC} composer install
	@echo "Launch the website at http://localhost"

reset-dev:
	${COMPOSE} down -v
	make install

docker-up:	## Start docker services for the project
	${COMPOSE} up -d

docker-down:	## Stop docker services for the project
	${COMPOSE} down

cli:	## Log into php container
	${EXEC} bash

test:	## Run tests
	${EXEC} bin/phpunit --coverage-html coverage

check-csfix:
	${EXEC} vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --allow-risky=yes --dry-run

csfix:
	${EXEC} vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --allow-risky=yes
