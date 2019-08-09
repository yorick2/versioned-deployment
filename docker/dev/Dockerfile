FROM php:7.2.9-apache
# user guid for the base container
# https://github.com/docker-library/docs/blob/master/php/README.md

MAINTAINER Name<email>

# install required programs
RUN apt-get update && apt-get install -y \
    sudo \
    wait-for-it \
    curl \
    vim \
    git\
    # install php extensions
    libmcrypt-dev \
    zlib1g-dev \
    sqlite3 \
    libsqlite3-dev \
    libssh2-1 \
    libssh2-1-dev \
    sshpass \
    && pecl install ssh2-1.1.2 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# php packages
RUN docker-php-ext-install \
    mbstring \
    tokenizer \
    zip \
    mysqli \
    pdo_mysql

RUN docker-php-ext-enable ssh2

# Enable rewrite module
RUN a2enmod rewrite

# Install PECL extensions
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/php.ini

# create ssh keys
RUN mkdir -p /home/www-data/.ssh
RUN ssh-keygen -t rsa -f /home/www-data/.ssh/id_rsa -P ''
RUN chown www-data:www-data -R /home/www-data

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Manually set up the apache environment variables
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

#ONBUILD RUN composer install
#ONBUILD RUN composer install \
#    --prefer-dist \
#    --optimize-autoloader \
#    --no-scripts \
#    --no-dev \
#    --profile \
#    --ignore-platform-reqs \
#    -vvv

# add a user that will be the same as my user so dosnt cause permission issues
RUN useradd -u 1000 -s /bin/bash docker
RUN echo docker:password | chpasswd
RUN usermod -a -G sudo docker
RUN usermod -a -G www-data docker

# prepare laravel for use
CMD wait-for-it version_deployment_db:3306 -- /code/setup_test_laravel.sh; sleep infinity