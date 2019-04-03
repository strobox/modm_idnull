FROM afram/wp_xdebug:fpm

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
	composer --version

# Mongodb driver
RUN pecl install mongodb && \
    echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/docker-php-ext-mongodb.ini


# PDO MYSQL
RUN docker-php-ext-install pdo_mysql