FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    zlib-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    curl

RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath tokenizer xml ctype zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
