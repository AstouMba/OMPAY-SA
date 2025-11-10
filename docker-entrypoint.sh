#!/bin/sh

# Générer la clé d'application si non existante
php artisan key:generate --force

# Optimiser config, routes et vues
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Lancer le serveur Laravel
php artisan serve --host=0.0.0.0 --port=8000
