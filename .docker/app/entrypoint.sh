#!/bin/bash

### Front-End
cd /var/www/frontend && npm install

### Back-End
cd /var/www/backend

#On error no such file entrypoint.sh, execute in terminal - dos2unix .docker\entrypoint.sh
chown -R www-data:www-data /var/www
if [! -f ".env"]; then
    cp /var/www/backend/.env.example /var/www/backend/.env
fi
if [! -f ".env.testing"]; then
    cp /var/www/backend/.env.testing.example /var/www/backend/.env.testing
fi
composer install
php artisan key:generate
php artisan migrate

php-fpm
