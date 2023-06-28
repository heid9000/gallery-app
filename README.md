# Api конвертации & кеширования изображений

## Список расширений
- gd
- imagick
- xdebug
- mysqli

## О проекте
0. Стэк: Symfony 6, Mariadb, Docker, Supervisord, Cron
1. Список размеров для конвертера:
    - "big" - 800 * 600
    - "med" - 640 * 480
    - "min" - 320 * 240
    - "mic" - 150 * 150

2. Файлы в app/public/images могут быть произвольного размера.
3. Файлы в app/public/cached должны соотв. требуемым размерам и быть доступны через api: /cache?name=&size
4. формат имени файлов в images: $name.$type, в cached: $name_$size.$type


## Запуск и проверка
1. `make rebuild`
2. `make install`
3. После запуска ресурс находится по адрессу `http://localhost:8080`

## API
- http://localhost:8080/cache?name=image_name&size=size_name
- картинки находятся в директории ./app/public/images
- при добавлении картинки в директорию кеш записывается в ./app/public/cached
- за генерацию кеша отвечает команда bin/console app:sync-images и `cron`

## Схема БД
Image -- мета информация о файле картинки
-----------
```1 ко многим с Cached```

Dimension -- требуемые разрешения кешей
-----------
```1 ко многим с Cached```

Cached -- мета информация о файле кеша
-----------
```много к одному с Image и Dimension```


## Остальное
- пользователь для composer admin (1000:1001) -> ./.env
- Код приложения находится в `./app`
- MariaDB можно найти по адресу `localhost:9887 db:mydb`
- Подключится к сервису php: `make cli`
