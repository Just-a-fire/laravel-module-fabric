# Stage 1: Базовый образ с расширениями
FROM php:8.1-fpm-alpine AS base

WORKDIR /var/www

RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev git unzip bash

RUN apk add nodejs \
    npm

# для сборки расширения pdo_sqlite
RUN apk add --no-cache sqlite-dev

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# На Alpine нужно убедиться, что у www-data есть права на рабочую директорию
RUN chown -R www-data:www-data /var/www

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]

# Stage 2: Разработка (с Xdebug)
FROM base AS local

# Используем проверенный скрипт для установки расширений (решает проблему с /tmp/pear)
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions xdebug-3.2.0

RUN printf "zend_extension=xdebug\n\
xdebug.mode=debug\n\
xdebug.client_host=host.docker.internal\n\
xdebug.client_port=9003\n\
xdebug.start_with_request=yes\n\
xdebug.log=/dev/stderr\n\
xdebug.log_level=0" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

USER www-data

# Stage 3: Продакшен (оптимизация)
FROM base AS production

COPY ./backend/composer.* ./backend/package.* ./
RUN composer install --no-dev --no-scripts --optimize-autoloader
RUN npm install

COPY ./backend .

RUN composer dump-autoload --optimize --no-dev
RUN npm run build

# Возвращаем права пользователю
RUN chown -R www-data:www-data /var/www
USER www-data