#!/bin/sh
set -e

ENV_FILE="/var/www/.env"
EXAMPLE_FILE="/var/www/.env.example"

# 1. Если .env нет, копируем его из example
if [ ! -f "$ENV_FILE" ]; then
    echo "Creating .env from .env.example..."
    cp "$EXAMPLE_FILE" "$ENV_FILE"
    
    php artisan config:clear
    # Генерируем ключ приложения (APP_KEY), если его нет
    php artisan key:generate --no-interaction
fi

# 2. Комментирование переменных окружения
if [ -f "$ENV_FILE" ]; then
    KEYS="APP_ENV DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD"
    for key in $KEYS; do
        sed -i "s/^\($key=\)/# \1/g" "$ENV_FILE"
    done
    echo "Local .env file sanitized."
fi

exec "$@"
