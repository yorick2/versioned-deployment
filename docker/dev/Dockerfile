FROM php:7.2.9-apache
# user guid for the base container
# https://github.com/docker-library/docs/blob/master/php/README.md

MAINTAINER Name<email>

RUN apt-get update

# install required programs
RUN apt-get install -y curl vim git

# install php extensions
RUN docker-php-ext-install mysqli


#CMD php artisan serve --host=0.0.0.0 --port=8181

# Install PECL extensions
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug


# Install Composer.
#RUN cd ~ && curl -sS https://getcomposer.org/installer | php
#RUN cp ~/composer.phar /usr/local/bin/composer
#RUN chmod +x /usr/local/bin/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer



# Manually set up the apache environment variables
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid
