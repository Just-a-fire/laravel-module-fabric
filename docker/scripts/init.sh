#!/bin/bash

# Функция проверки порта (работает через nc или lsof)
is_port_busy() {
    (echo >/dev/tcp/localhost/$1) >/dev/null 2>&1
}

# 1. Копируем .env из примеров, если их нет
[ ! -f .env ] && cp .env.example .env && echo "Created root .env"

# 2. Читаем все переменные, содержащие PORT, из .env
PORTS=$(grep "PORT" .env | grep -v "^#" | cut -d '=' -f 2)

for PORT in $PORTS; do
    if is_port_busy $PORT; then
        VAR_NAME=$(grep "=$PORT" .env | cut -d '=' -f 1)
        echo "Warning: Port $PORT (from $VAR_NAME) is already in use!"
        
        read -p "Enter new port for $VAR_NAME [or press Enter to keep $PORT]: " NEW_PORT
        
        if [ ! -z "$NEW_PORT" ]; then
            # Меняем порт в .env файле
            sed -i "s/^$VAR_NAME=.*/$VAR_NAME=$NEW_PORT/" .env
            echo "$VAR_NAME updated to $NEW_PORT"
        fi
    fi
done

echo "Environment check complete."
