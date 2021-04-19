#!/bin/bash

#On error no such file entrypoint.sh, execute in terminal - dos2unix .docker\entrypoint.sh
chown -R www-data:www-data /var/www
php /var/www/backend/artisan config:clear
php /var/www/backend/artisan cache:clear

php-fpm
