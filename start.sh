#!/bin/bash
cd sistema_vuelos
composer install --no-interaction --optimize-autoloader
php artisan migrate --force
php artisan key:generate --force
php artisan serve --host=0.0.0.0 --port=$PORT
