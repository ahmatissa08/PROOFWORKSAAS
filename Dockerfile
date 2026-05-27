FROM dunglas/frankenphp:1-php8.2

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip

RUN install-php-extensions \
    bcmath \
    pdo_mysql \
    mbstring \
    curl \
    dom \
    fileinfo \
    openssl \
    tokenizer \
    xml \
    ctype \
    session \
    zip

WORKDIR /app

COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true
RUN mkdir -p storage/framework/cache/data storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD sh -c "php artisan config:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
