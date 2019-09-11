FROM php:7.2.9-apache
# user guid for the base container
# https://github.com/docker-library/docs/blob/master/php/README.md

MAINTAINER Name<email>

ARG SSH_MASTER_USER
ARG SSH_MASTER_PASS

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
    sudo \
    openssh-server \
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

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Manually set up the apache environment variables
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

# add ssh
COPY ssh_config /etc/ssh/ssh_config
COPY sshd_config /etc/ssh/sshd_config

COPY user.sh /usr/local/bin/user.sh
RUN chmod +x /usr/local/bin/user.sh
RUN /usr/local/bin/user.sh
RUN rm /usr/local/bin/user.sh

## allow access to web folder
RUN chmod -R g+rwx /var/www
RUN chown :${SSH_MASTER_USER} /var/www
RUN chmod -R g+rwx /var/www/html-old
RUN chown :${SSH_MASTER_USER} /var/www/html-old
RUN echo '<?php echo phpinfo(); ' > /var/www/html-old/index.php
RUN chown ${SSH_MASTER_USER}:${SSH_MASTER_USER} /var/www/html-old/index.php

RUN ln -s /var/www/html-old /var/www/html

# run services you want to continue to run in a single string of commands,
# which finishes with a command non ending. This is cos docker closes services when complete.
CMD service ssh start && apache2ctl start && tail -f /dev/null
