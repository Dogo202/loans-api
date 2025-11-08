FROM php:8.4-fpm-alpine
RUN apk add --no-cache git unzip libpq-dev postgresql-client icu-dev \
 && docker-php-ext-install intl pdo pdo_pgsql
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app