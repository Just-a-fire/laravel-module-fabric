# Stage 1: Базовый образ с расширениями
FROM php:8.1-fpm-alpine AS base

RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev git unzip

RUN apk add nodejs \
    npm

# для сборки расширения pdo_sqlite
RUN apk add --no-cache sqlite-dev

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www

# Stage 2: Разработка (с Xdebug)
FROM base AS local

RUN apk add --no-cache $PHPIZE_DEPS linux-headers \
    && pecl channel-update pecl.php.net \
    && pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug \
    && rm -rf /tmp/pear 

COPY <<EOF /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
zend_extension=xdebug
xdebug.mode=debug
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.start_with_request=yes
# xdebug.log=/var/log/xdebug.log
xdebug.log=/dev/stderr
xdebug.log_level=0
EOF

USER www-data

# Stage 3: Продакшен (оптимизация)
FROM base AS production
COPY . .
RUN composer install --no-dev --optimize-autoloader
# Сборка фронтенда Vite (при условии, что фронтенд объединён)
RUN npm install && npm run build

USER www-data

ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]