FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl libsqlite3-dev zip unzip \
    && docker-php-ext-install pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 8000

CMD php artisan config:clear && \
    php artisan migrate:fresh --seed --force && \
    php artisan serve --host=0.0.0.0 --port=8000
