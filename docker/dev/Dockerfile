FROM php:7.2.9-apache
# user guid for the base container
# https://github.com/docker-library/docs/blob/master/php/README.md

MAINTAINER Name<email>

# install required programs
RUN apt-get update && apt-get install -y \
    curl \
    vim \
    git\
    # install php extensions
    libmcrypt-dev \
    git \
    zlib1g-dev \
    sqlite3 \
    libsqlite3-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# php packages
RUN docker-php-ext-install \
    mbstring \
    tokenizer \
    zip \
    mysqli \
 #   pdo \
    pdo_mysql


# Enable rewrite module
RUN a2enmod rewrite


# Install PECL extensions
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/php.ini

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Manually set up the apache environment variables
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid


ONBUILD RUN composer install
#ONBUILD RUN composer install \
#    --prefer-dist \
#    --optimize-autoloader \
#    --no-scripts \
#    --no-dev \
#    --profile \
#    --ignore-platform-reqs \
#    -vvv

ONBUILD RUN php artisan clear-compiled
ONBUILD RUN php artisan optimize
ONBUILD RUN php artisan config:cache
ONBUILD RUN php artisan migrate

# Configure directory permissions for the web server
ONBUILD RUN chgrp -R www-data storage /var/www/html/bootstrap/cache
ONBUILD RUN chmod -R ug+rwx storage /var/www/html/bootstrap/cache

ONBUILD RUN chgrp -R www-data storage /var/www/html/storage
ONBUILD RUN chmod -R ug+rwx storage /var/www/html/storage

# Configure data volume
ONBUILD VOLUME /var/www/html/storage/framework/sessions
ONBUILD VOLUME /var/www/html/storage/logs
