#!/bin/bash

#On error no such file entrypoint.sh, execute in terminal - dos2unix .docker\entrypoint.sh
chown -R www-data:www-data /var/www
php /var/www/artisan config:clear
php /var/www/artisan cache:clear

php-fpm
