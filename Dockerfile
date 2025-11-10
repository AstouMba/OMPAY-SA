# Étape 1: Build des dépendances PHP avec Composer
FROM composer:2.6 AS composer-build

WORKDIR /app

# Copier uniquement composer.json et composer.lock
COPY composer.json composer.lock ./

# Installer les dépendances PHP (sans dev ni scripts post-install)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Étape 2: Image finale PHP-FPM Alpine
FROM php:8.3-fpm-alpine

# Installer les extensions PHP nécessaires pour PostgreSQL
RUN apk add --no-cache postgresql-dev bash git unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Créer un utilisateur non-root
RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les dépendances installées depuis l'étape de build
COPY --from=composer-build /app/vendor ./vendor

# Copier le reste du code de l'application
COPY . .

# Créer les répertoires nécessaires et définir les permissions
RUN mkdir -p storage/framework/{cache,data,sessions,testing,views} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R laravel:laravel /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Copier le script d'entrée
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Passer à l'utilisateur non-root pour exécuter Laravel
USER laravel

# Exposer le port 8000 (artisan serve)
EXPOSE 8000

# Commande par défaut pour le container
CMD ["docker-entrypoint.sh"]
