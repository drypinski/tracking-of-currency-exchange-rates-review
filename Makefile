init: init-ci
init-ci: docker-down-clear \
	api-clear \
	docker-pull docker-build docker-up \
	api-init

up: docker-up
down: docker-down
clear: docker-down-clear
restart: down up
check: lint test

lint: api-lint
lint-fix: api-cs-fix

test: api-test
test-functional: api-test-functional
test-unit: api-test-unit


docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

logs:
	docker compose logs -f

docker-build:
	docker compose build --pull

php: api-run-php

# =================
# === TOOLS =======
# =================
tools-install:
	COMPOSER_MEMORY_LIMIT=-1 docker compose run --rm api-php-cli sh -c 'composer install --working-dir=tools/php-cs-fixer'
	COMPOSER_MEMORY_LIMIT=-1 docker compose run --rm api-php-cli sh -c 'composer install --working-dir=tools/phplint'

# ====================
# === API ============
# ====================
api-init: api-composer-install api-wait-db api-migrations-migrate api-fixtures-load api-cache-clear

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf vendor/* var/*'

api-update-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'mkdir -p var && chmod a+w -R var'

api-composer-install:
	COMPOSER_MEMORY_LIMIT=-1 docker compose run --rm api-php-cli sh -c 'composer install --no-interaction --no-scripts'

api-wait-db:
	until docker compose exec -T api-db pg_isready --timeout=60 --dbname=api ; do sleep 1 ; done

api-migrations-migrate:
	docker compose run --rm api-php-cli sh -c 'bin/console doctrine:migrations:migrate --no-interaction'

api-fixtures-load:
	docker compose run --rm api-php-cli sh -c 'bin/console doctrine:fixtures:load --no-interaction'

api-cache-clear:
	docker compose run --rm api-php-cli sh -c 'bin/console cache:clear && bin/console cache:warmup'

api-lint:
	docker compose run --rm api-php-cli composer lint
	docker compose run --rm api-php-cli composer php-cs-fixer fix -- --dry-run --diff

api-cs-fix:
	docker compose run --rm api-php-cli composer php-cs-fixer fix

api-test-database-create:
	docker compose run --rm api-php-cli sh -c 'bin/console doctrine:database:create -n -e test --if-not-exists'

api-test-database-drop:
	docker compose run --rm api-php-cli sh -c 'bin/console doctrine:database:drop -e test --force --if-exists'

api-test-migrations-migrate:
	docker compose run --rm api-php-cli sh -c 'bin/console doctrine:migrations:migrate -n -e test'

api-test:
	docker compose run --rm api-php-cli composer test

api-test-functional:
	docker compose run --rm api-php-cli composer test -- --testsuite=functional

api-test-unit:
	docker compose run --rm api-php-cli composer test -- --testsuite=unit

api-run-php:
	docker compose run --rm api-php-cli bash

api-doctrine-schema-validate:
	docker compose run --rm api-php-cli sh -c 'bin/console doctrine:schema:validate -v --skip-sync'
