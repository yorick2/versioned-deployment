#!/bin/bash
composer install --no-interaction --working-dir=/var/www/html
if [[ $(php artisan migrate:status) = *"No migrations found"* ]]; then
    php /var/www/html/artisan clear-compiled
    php /var/www/html/artisan optimize
    php /var/www/html/artisan config:cache
    php /var/www/html/artisan migrate
    php /var/www/html/artisan db:seed
fi
echo '
███████╗██╗████████╗███████╗    ██████╗ ███████╗ █████╗ ██████╗ ██╗   ██╗
██╔════╝██║╚══██╔══╝██╔════╝    ██╔══██╗██╔════╝██╔══██╗██╔══██╗╚██╗ ██╔╝
███████╗██║   ██║   █████╗      ██████╔╝█████╗  ███████║██║  ██║ ╚████╔╝
╚════██║██║   ██║   ██╔══╝      ██╔══██╗██╔══╝  ██╔══██║██║  ██║  ╚██╔╝
███████║██║   ██║   ███████╗    ██║  ██║███████╗██║  ██║██████╔╝   ██║
╚══════╝╚═╝   ╚═╝   ╚══════╝    ╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝╚═════╝    ╚═╝'