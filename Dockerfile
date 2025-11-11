# Étape 1: Build des dépendances PHP
FROM composer:2.6 AS composer-build

RUN apk add --no-cache autoconf g++ make libpng-dev libjpeg-turbo-dev freetype-dev \
    && docker-php-ext-install gd \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    --no-scripts --ignore-platform-req=ext-pcntl --ignore-platform-req=ext-gd


# Étape 2: Image finale
FROM php:8.3-fpm-alpine

# Extensions PHP
RUN apk add --no-cache postgresql-dev postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql

# Ajouter utilisateur
RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

WORKDIR /var/www/html

# Copier vendor depuis l'étape précédente
COPY --from=composer-build /app/vendor ./vendor

# Copier tout le projet
COPY . .

# Préparer les dossiers et permissions AVANT USER laravel
RUN mkdir -p storage/framework/{cache,data,sessions,testing,views} \
    && mkdir -p storage/logs storage/api-docs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R laravel:laravel /var/www/html

# Script d'entrée
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Maintenant on peut passer à l'utilisateur laravel
USER laravel

EXPOSE 8000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
