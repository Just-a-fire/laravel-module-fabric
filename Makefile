.PHONY: init up down build shell

help: ## Показать список доступных команд
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

init: ## Полная инициализация проекта
	@echo "Preparing scripts permissions..."
	@chmod +x docker/scripts/init.sh
	@sh docker/scripts/init.sh
	@$(MAKE) build
	@$(MAKE) up
	@echo "Waiting for containers to initialize..."
	@for i in {5..1}; do echo -n "$$i.. "; sleep 1; done; echo "GO!"
# 	@docker compose exec app php artisan migrate --seed
	@docker compose exec app php artisan module:migrate --seed Fabric
	@echo "Project is ready at http://localhost:$$(grep PROJECT_PORT_HTTP .env | cut -d '=' -f 2)"

# Сборка с учетом APP_ENV из .env
build: ## Пересобрать образы
	docker compose build --no-cache

up: ## Запустить контейнеры
	docker compose up -d

down: ## Остановить контейнеры
	docker compose down

shell: ## Быстрый вход в консоль PHP
	docker compose exec app sh
