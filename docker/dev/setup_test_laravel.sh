#!/bin/bash

# add private key to authorised keys on example.com container
echo "-----BEGIN PUBLIC KEY-----" >> /code/deploy-target/.ssh/authorized_keys
cat /home/www-data/.ssh/id_rsa.pub >> /code/deploy-target/.ssh/authorized_keys
echo "-----END PUBLIC KEY-----" >> /code/deploy-target/.ssh/authorized_keys

# setup laravel
apache2ctl restart
chgrp -R www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R ug+w /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
composer install --no-interaction --working-dir=/var/www/html

if [[ $(php artisan migrate:status) = *"No migrations found"* ]] || [[ $(php artisan migrate:status) = *"Undefined index"* ]]; then
    echo "runnning migration"
    php /var/www/html/artisan clear-compiled
    php /var/www/html/artisan optimize
    php /var/www/html/artisan key:generate
    php /var/www/html/artisan config:cache
    php /var/www/html/artisan migrate
    php /var/www/html/artisan db:seed
fi

# ensure no unusual log files are created owned by root
chgrp -R www-data /var/www/html/storage 

echo '
███████╗██╗████████╗███████╗    ██████╗ ███████╗ █████╗ ██████╗ ██╗   ██╗
██╔════╝██║╚══██╔══╝██╔════╝    ██╔══██╗██╔════╝██╔══██╗██╔══██╗╚██╗ ██╔╝
███████╗██║   ██║   █████╗      ██████╔╝█████╗  ███████║██║  ██║ ╚████╔╝
╚════██║██║   ██║   ██╔══╝      ██╔══██╗██╔══╝  ██╔══██║██║  ██║  ╚██╔╝
███████║██║   ██║   ███████╗    ██║  ██║███████╗██║  ██║██████╔╝   ██║
╚══════╝╚═╝   ╚═╝   ╚══════╝    ╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝╚═════╝    ╚═╝'
