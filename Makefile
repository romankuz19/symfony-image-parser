.PHONY: install

install:
	@echo "Running composer install..."
	@composer install
	@echo "Running npm install..."
	@npm install
	@echo "Running npm run watch..."
	@npm run watch