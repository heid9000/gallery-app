FROM php:8.1-fpm-alpine
RUN apk add --no-cache mysql-client msmtp perl wget procps shadow libzip libpng libjpeg-turbo libwebp freetype icu icu-data-full

RUN apk add --no-cache --virtual build-essentials \
    icu-dev icu-libs zlib-dev g++ make automake autoconf libzip-dev linux-headers \
    libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev  && \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd && \
    docker-php-ext-install mysqli && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install intl && \
    docker-php-ext-install opcache && \
    docker-php-ext-install exif && \
    docker-php-ext-install zip

ENV MAGICK_HOME=/usr
RUN apk add --no-cache imagemagick imagemagick-dev && \
    pecl install imagick-3.7.0 && docker-php-ext-enable imagick

RUN pecl install xdebug-3.2.1 && docker-php-ext-enable xdebug
RUN apk del build-essentials && rm -rf /usr/src/php*

RUN apk add --no-cache nginx supervisor make
RUN wget https://getcomposer.org/composer-stable.phar -O /usr/local/bin/composer && chmod +x /usr/local/bin/composer

ARG UID
RUN adduser -h /var/www -u $UID -G root -D admin

COPY ./start.sh /start.sh
RUN chmod +x /start.sh

WORKDIR /var/www/app
CMD /start.sh
