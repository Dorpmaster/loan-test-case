.SILENT: ;               # no need for @
.ONESHELL: ;             # recipes execute in same shell
.NOTPARALLEL: ;          # wait for this target to finish
.EXPORT_ALL_VARIABLES: ; # send all vars to shell
default: help ;   		 # default target
Makefile: ;              # skip prerequisite

# use the rest as arguments for "run"
RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
# ...and turn them into do-nothing targets
$(eval $(RUN_ARGS):;@:)

.PHONY: help
help:
	echo "help: Shows Help menu"
	echo "composer: Runs composer"
	echo "up: Runs application"
	echo "down: Stops the application and removes all resources"
	echo "dev: Starts the application in the development mode"
	echo "test: Runs tests"
	echo "phpcs: PHP Code Sniffer"
	echo "phpcs-fix: PHP Code Sniffer in the fix mode"
	echo "phpstan: PHP Standards"
	echo "phpstan-baseline: Adds exceptions of PHP Standards"

.PHONY: composer
composer:
	docker run --rm --interactive --tty \
		--volume $(PWD):/srv/app \
		--volume $(HOME)/.cache/composer:/var/composer/cache \
		--workdir /srv/app \
		composer \
		composer $(RUN_ARGS)

.PHONY: up
up:
	docker compose up -d --remove-orphans

.PHONY: down
down:
	docker compose down -v -t 0

.PHONY: dev
dev:
	docker compose watch

.PHONY: test
test:
	$(MAKE) test-build
	$(MAKE) test-up
	$(MAKE) -i test-run
	$(MAKE) test-down

.PHONY: test-build
test-build:
	docker compose --project-name loan-test build

.PHONY: test-up
test-up:
	docker compose --project-name loan-test up -d --remove-orphans

test-run:
	docker compose --project-name loan-test exec --interactive php composer test

.PHONY: test-down
test-down:
	docker compose --project-name loan-test down -v -t 0

.PHONY: phpcs
phpcs:
	docker run --rm --tty \
	  --workdir /srv/app \
	  --volume `pwd`:/srv/app \
	  composer composer phpcs

.PHONY: phpcs-fix
phpcs-fix:
	docker run --rm --tty \
	  --workdir /srv/app \
	  --volume `pwd`:/srv/app \
	  composer composer phpcs:fix

.PHONY: convert
convert:
	docker run --rm --tty \
	  --workdir /srv/app \
	  --volume `pwd`:/srv/app \
	  php:8.3-cli vendor/bin/config-transformer convert config

.PHONY: console
console:
	docker compose run --rm --tty \
	  --workdir /srv/app \
	  --volume `pwd`:/srv/app \
	  php bin/console $(RUN_ARGS)
