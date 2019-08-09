#!/bin/bash
apache2ctl restart
chgrp -R www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R ug+w /var/www/html/storage /var/www/html/bootstrap/cache
chmod 777 /var/www/html/.env
composer install --no-interaction --working-dir=/var/www/html

if [[ $(php artisan migrate:status) = *"No migrations found"* ]]; then
    php /var/www/html/artisan clear-compiled
    php /var/www/html/artisan optimize
    php /var/www/html/artisan key:generate
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