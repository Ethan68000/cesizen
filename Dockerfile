FROM php:8.4-fpm-alpine AS base
RUN apk add --no-cache git curl libzip-dev zip unzip mysql-client postgresql-client icu-dev icu-data-full
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql zip opcache intl
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

FROM base AS dev
RUN apk add --no-cache bash vim make
COPY . .
RUN composer install --no-interaction --no-progress
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var/ \
    && chmod -R 775 var/
EXPOSE 9000
CMD ["php-fpm"]

FROM base AS production
ARG APP_ENV=prod
ARG APP_VERSION=latest
ENV APP_ENV=${APP_ENV}
ENV APP_VERSION=${APP_VERSION}
COPY --chown=www-data:www-data . .
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader
RUN mkdir -p var/cache var/log \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/var


COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]