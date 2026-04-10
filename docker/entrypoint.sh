#!/bin/sh
set -e

cd /var/www

if [ -z "${APP_KEY:-}" ]; then
    echo "APP_KEY masih kosong. Isi APP_KEY di file .env sebelum container dijalankan."
    exit 1
fi

mkdir -p \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache

# Always recreate the storage symlink so deployments recover from stale or broken links.
php artisan storage:link --force --no-interaction

# Always clear stale caches first so env changes are picked up even when RUN_OPTIMIZE=false.
php artisan optimize:clear --no-interaction

if [ "${RUN_OPTIMIZE:-true}" = "true" ]; then
    php artisan config:cache --no-interaction
    php artisan route:cache --no-interaction
    php artisan view:cache --no-interaction
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    attempt=1
    max_attempts="${MIGRATION_MAX_ATTEMPTS:-12}"
    retry_delay="${MIGRATION_RETRY_DELAY:-5}"

    until php artisan migrate --force --no-interaction; do
        if [ "$attempt" -ge "$max_attempts" ]; then
            echo "Migrasi database gagal setelah ${attempt} kali percobaan."
            exit 1
        fi

        echo "Database belum siap, ulangi migrasi dalam ${retry_delay} detik..."
        attempt=$((attempt + 1))
        sleep "$retry_delay"
    done
fi

exec "$@"
