# Laravel Module Fabric

## Запуск проекта

### **Makefile**
```bash
make init
```

### Если нет утилиты **make**

1. Сборка
```bash
chmod +x docker/scripts/init.sh
sh docker/scripts/init.sh

docker compose build --no-cache
docker compose up -d
```

2. Миграции и сиды
```bash
docker compose exec app php artisan module:migrate --seed Fabric
```

## Перейдите по адресу http://localhost:8097/fabrics
Порт из переменной `.env` `PROJECT_PORT_HTTP`

## Запуск тестов в Docker
```bash
docker-compose exec app php artisan test --filter FabricApiTest
docker-compose exec app php artisan test --filter FabricDataServiceTest
```

--- 
`backend` и `frontend` объединены для компактности модуля.

