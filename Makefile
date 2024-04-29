.PHONY: install up

install: up
	@echo "Running composer install..."
	@composer install
	@echo "Running npm install..."
	@npm install

up:
	@echo "Running docker-compose up -d..."
	@docker-compose up -d