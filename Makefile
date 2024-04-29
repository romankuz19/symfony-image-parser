.PHONY: install up

install: up
	@echo "Running composer install..."
	@composer install
	@echo "Running npm install..."
	@npm install
	@echo "Running npm run watch..."
	@npm run watch

up:
	@echo "Running docker-compose up -d..."
	@docker-compose up -d