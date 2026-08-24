FROM php:8.4-fpm-alpine AS base

RUN apk add --no-cache git curl libzip-dev zip unzip mysql-client postgresql-client
RUN docker-php-ext-install pdo pdo_mysql zip opcache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

FROM base AS dev
RUN apk add --no-cache bash vim make
COPY . .
RUN composer install --no-interaction --no-progress
EXPOSE 9000
CMD ["php-fpm"]

FROM base AS production
ARG APP_ENV=prod
ARG APP_VERSION=latest
ENV APP_ENV=${APP_ENV}
ENV APP_VERSION=${APP_VERSION}
COPY --chown=www-data:www-data . .
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader
RUN chmod -R 755 /var/www/html && chmod -R 775 /var/www/html/var
EXPOSE 9000
CMD ["php-fpm"]