FROM php:8.4-cli-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        default-mysql-client \
        git \
        gosu \
        libonig-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-install mbstring pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupadd --gid 1000 app \
    && useradd --uid 1000 --gid app --shell /bin/sh --create-home app

WORKDIR /var/www/html

COPY composer.json ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts
COPY . .

COPY docker/entrypoint.sh /usr/local/bin/laravel-entrypoint
RUN chmod +x /usr/local/bin/laravel-entrypoint

EXPOSE 8000

ENTRYPOINT ["laravel-entrypoint"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
