# Use the PHP 8.2.2 FPM base image
FROM php:8.2.2-fpm

# Update the package lists and fix any missing dependencies
RUN apt-get update --fix-missing

# Install some common packages and development tools
RUN apt-get install -y curl wget zip unzip git g++ nano 

# Install required libraries for PHP extensions
RUN apt-get install -y libfreetype6-dev libjpeg-dev libpng-dev libonig-dev libxml2-dev libzip-dev libldap2-dev libwebp-dev

# Clean up the package cache to reduce the size of the image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure and install the GD extension for PHP
RUN set -e; docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype && docker-php-ext-install gd

# Install additional PHP extensions
RUN docker-php-ext-install exif intl pdo pdo_mysql

# Set the working directory for the application
WORKDIR /usr/share/nginx/www

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Xdebug and enable it
RUN pecl install xdebug \ 
    && docker-php-ext-enable xdebug

# Create a group and user to run the application
RUN groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Copy the application files to the container and set the ownership
COPY --chown=www:www . /

# Switch to the new user to run the application
USER www

# Define the command to start the PHP-FPM server
CMD ["php-fpm"]
