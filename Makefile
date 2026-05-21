COMPOSE = docker compose

.PHONY: up down logs shell lint

up:
	$(COMPOSE) up -d --build

down:
	$(COMPOSE) down

logs:
	$(COMPOSE) logs -f app

shell:
	$(COMPOSE) exec app bash

lint:
	find . -maxdepth 1 -name '*.php' -print0 | xargs -0 -n1 php -l
