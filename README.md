# Bible Reading App

Legacy PHP/MySQL web app for Bible reading plans (Chinese + English pages).

## Project layout

- `*.php`: current application entrypoints and pages (legacy flat structure).
- `mystyle.css`, `w3.css`: stylesheets.
- `bible.jfif`: login/banner asset.
- `docker/`: local container build/runtime files.
- `docker-compose.yml`: local development stack (PHP + MySQL + phpMyAdmin).
- `Makefile`: common developer commands.
- `.env.example`: environment values for Docker/dev setup.
- `connect.example.php`: database connection template.

## Quick start (Docker)

1. Copy env values:
   ```bash
   cp .env.example .env
   ```
2. Create app DB config:
   ```bash
   cp connect.example.php connect.php
   ```
   Then adjust credentials if needed.
3. Start services:
   ```bash
   make up
   ```
4. Open:
   - App: <http://localhost:8080>
   - phpMyAdmin: <http://localhost:8081>

## Make targets

- `make up` - start stack in background.
- `make down` - stop stack.
- `make logs` - tail app logs.
- `make shell` - open shell in PHP container.
- `make lint` - PHP syntax checks for all PHP files.

## Notes

- `connect.php` is intentionally environment-specific and should not contain production secrets.
- This repository currently uses a flat file layout; see `PROJECT_REVIEW.md` for staged refactor guidance.
