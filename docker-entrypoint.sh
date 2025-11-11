#!/bin/sh

echo "Starting docker-entrypoint.sh"

# Check required DB env variables
MISSING=0
for v in DB_HOST DB_PORT DB_DATABASE DB_USERNAME; do
  eval val="\$$v"
  if [ -z "$val" ]; then
    echo "Required env $v is not set"
    MISSING=1
  fi
done

if [ "$MISSING" -eq 1 ]; then
  echo "One or more required DB env vars are missing. Aborting." >&2
  exit 1
fi

# Generate APP_KEY if missing
if [ -z "$APP_KEY" ]; then
  echo "APP_KEY not set — generating one"
  php artisan key:generate --force || true
fi

echo "Waiting for database to be ready..."
MAX_WAIT=120
WAITED=0
while ! pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" >/dev/null 2>&1; do
  if [ "$WAITED" -ge "$MAX_WAIT" ]; then
    echo "Timeout waiting for database after ${MAX_WAIT}s" >&2
    exit 1
  fi
  echo "DB unavailable - sleeping 1s (waited ${WAITED}s)"
  sleep 1
  WAITED=$((WAITED+1))
done

echo "Database is up - executing runtime init steps"

# Clear caches
php artisan route:clear || true
php artisan config:clear || true
php artisan cache:clear || true

echo "Ensuring storage directories exist..."
mkdir -p storage/api-docs
mkdir -p storage/framework/{cache,data,sessions,testing,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache

echo "Creating storage symlink..."
php artisan storage:link || true

echo "Fixing permissions..."
chmod -R 775 storage bootstrap/cache

echo "Generating Swagger documentation..."
php artisan l5-swagger:generate --force || echo "⚠ Swagger generation failed (continuing)"

if [ -f "storage/api-docs/api-docs.json" ]; then
  echo "✓ Swagger generated successfully"
else
  echo "⚠ Swagger file missing"
fi

echo "Running migrations..."
php artisan migrate --force || true

php artisan config:cache || true

echo "Starting Laravel app..."
exec "$@"
