FROM composer:2 AS build
WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

FROM php:8.5-cli-bookworm AS runtime
RUN apt-get update \
    && apt-get install -y --no-install-recommends libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY --from=build --chown=www-data:www-data /app /app
USER www-data
EXPOSE 8000
CMD ["php", "artisan", "vibe", "--host=0.0.0.0", "--port=8000"]
