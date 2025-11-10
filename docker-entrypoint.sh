#!/bin/sh

# Attendre que la base de données soit prête (optionnel)
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database connection..."
    while ! pg_isready -h $DB_HOST -p $DB_PORT -U $DB_USERNAME; do
        sleep 1
    done
    echo "Database is ready!"
fi

# Installer les clés Passport si elles n'existent pas
if [ ! -f storage/oauth-private.key ] || [ ! -f storage/oauth-public.key ]; then
    echo "Installing Passport keys..."
    php artisan passport:keys --force
fi

# Générer la clé d'application si non existante
php artisan key:generate --force

# Exécuter les migrations si demandé
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Exécuter les seeders si demandé
if [ "$RUN_SEEDERS" = "true" ]; then
    echo "Running database seeders..."
    php artisan db:seed --force
fi

# Optimiser config, routes et vues pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Lancer le serveur Laravel
echo "Starting Laravel server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
