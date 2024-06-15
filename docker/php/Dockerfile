# Use the PHP 8.2.2 FPM base image
FROM php:8.3-fpm

# Update the package lists and fix any missing dependencies
RUN apt-get update --fix-missing

# Install some common packages and development tools
RUN apt-get install -y curl wget zip unzip git g++ nano 

# Install required libraries for PHP extensions
RUN apt-get install -y libpng-dev libonig-dev libxml2-dev libzip-dev libldap2-dev wkhtmltopdf

# Clean up the package cache to reduce the size of the image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install additional PHP extensions
RUN docker-php-ext-install exif intl pdo pdo_mysql gd soap calendar gettext pcntl sockets zip ldap

# Install Xdebug and enable it
RUN pecl install xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create a group and user to run the application
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

# Set the working directory for the application
WORKDIR /usr/share/nginx/www

# Copy the application files to the container and set the ownership
COPY --chown=www:www . .

# Switch to the new user to run the application
USER www

# Define the command to start the PHP-FPM server
CMD ["php-fpm"]