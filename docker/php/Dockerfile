FROM php:8-fpm

RUN apt-get update --fix-missing \
    && apt-get install -y curl wget zip unzip git g++ \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Run docker-php-ext-install for available extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql opcache intl

# install xdebug
RUN pecl install xdebug \ docker-php-ext-enable xdebug 

COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Change uid and gid to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data