# Простой docker-compose шаблон для symfony6 приложений

## Список расширений
- gd
- imagick
- xdebug
- mysqli

## Запуск и проверка
1. Запустить: `make up`
3. После запуска ресурс находится по адрессу `http://localhost:8080`

## Остальное
- пользователь для composer admin (1000:1001) -> ./.env
- Код приложения находится в `./app`
- MariaDB можно найти по адресу `localhost:9887 db:mydb`
- Подключится к сервису php: `make cli`
