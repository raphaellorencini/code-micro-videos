#!/bin/bash

#On error no such file entrypoint.sh, execute in terminal - dos2unix .docker\entrypoint.sh
chown -R www-data:www-data /var/www
cp /var/www/template/.env.example /var/www/.env
cp /var/www/template/.env.testing.example /var/www/.env.testing
#composer install
#php artisan key:generate
#php artisan migrate

php-fpm
