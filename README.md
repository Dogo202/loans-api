# Loans API (Yii2 + Nginx + PostgreSQL + Docker)

#### API для подачи и обработки заявок на займ.

### Требования ТЗ

Сервер: http://localhost:80

БД: host=localhost, port=5432, dbname=loans, user=user, password=password

#### Эндпоинты:

POST /requests — создать заявку

GET /processor?delay=5 — обработать заявки (аппрув 10%, с задержкой sleep(delay))

#### Стек

PHP 8.3-fpm (Alpine), Yii2 basic

Nginx 1.25 (Alpine)

PostgreSQL 15 (Alpine)

Docker Compose, host network (порты 80/5432 на хосте должны быть свободны)

#### Быстрый старт
```bash
# клонируйте репозиторий
git clone <repo> loans-api && cd loans-api

# (опционально) пробросьте свой UID/GID для корректных прав
echo "HOST_UID=$(id -u)" > .env
echo "HOST_GID=$(id -g)" >> .env

# стопните хостовые nginx/postgres, чтобы освободить 80/5432
sudo systemctl stop nginx postgresql 2>/dev/null || true

# поднять стек
docker compose up -d --build

# применить миграции
docker compose exec php php yii migrate/up --interactive=0

```

### Проверка:
```bash
# создать заявку
curl -i -X POST http://localhost/requests \
  -H 'Content-Type: application/json' \
  -d '{"user_id":1,"amount":3000,"term":30}'

# запустить обработчик с задержкой 5 сек. на заявку
curl -i "http://localhost/processor?delay=5"
```


### Затраченно время на разработу - ~3 часа
