#!/usr/bin/env sh
set -eu

mkdir -p vendor storage bootstrap/cache
chown -R app:app vendor storage bootstrap/cache

gosu app git config --global --add safe.directory /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

set_env() {
    key="$1"
    value="$2"

    if grep -q "^${key}=" .env; then
        sed -i "s|^${key}=.*|${key}=${value}|" .env
    else
        printf '\n%s=%s\n' "$key" "$value" >> .env
    fi
}

set_env APP_NAME "${APP_NAME:-AstroSoft}"
set_env APP_ENV "${APP_ENV:-local}"
set_env APP_DEBUG "${APP_DEBUG:-true}"
set_env APP_KEY "${APP_KEY:-}"
set_env APP_URL "${APP_URL:-http://localhost:8001}"
set_env DB_CONNECTION "${DB_CONNECTION:-mysql}"
set_env DB_HOST "${DB_HOST:-mysql}"
set_env DB_PORT "${DB_PORT:-3306}"
set_env DB_DATABASE "${DB_DATABASE:-sistema_pruebas}"
set_env DB_USERNAME "${DB_USERNAME:-astrosoft}"
set_env DB_PASSWORD "${DB_PASSWORD:-astrosoft_dev_password}"
set_env CACHE_STORE "${CACHE_STORE:-database}"
set_env SESSION_DRIVER "${SESSION_DRIVER:-database}"
set_env QUEUE_CONNECTION "${QUEUE_CONNECTION:-database}"
set_env SANCTUM_STATEFUL_DOMAINS "${SANCTUM_STATEFUL_DOMAINS:-localhost:4200,127.0.0.1:4200}"
set_env FRONTEND_URL "${FRONTEND_URL:-http://localhost:4200}"
chown app:app .env

if [ ! -d vendor ] || [ ! -f vendor/autoload.php ]; then
    gosu app composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts
fi

if [ "${APP_ENV:-local}" = "production" ] && ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    echo "APP_KEY must be a persistent base64 key in production."
    exit 1
fi

if [ "${APP_ENV:-local}" != "production" ] && ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    gosu app php artisan key:generate --force
fi

if [ "${DB_CONNECTION:-}" = "mysql" ]; then
    until php -r "new PDO('mysql:host=${DB_HOST:-mysql};port=${DB_PORT:-3306};dbname=${DB_DATABASE:-sistema_pruebas}', '${DB_USERNAME:-astrosoft}', '${DB_PASSWORD:-astrosoft_dev_password}');" >/dev/null 2>&1; do
        echo "Waiting for MySQL..."
        sleep 3
    done

    if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
        gosu app php artisan migrate --force
    fi
fi

if [ "${APP_ENV:-local}" = "production" ]; then
    gosu app php artisan config:cache
fi

exec gosu app "$@"
