# Use the official PHP 8.3 image with FPM (FastCGI Process Manager)
FROM php:8.3-fpm

# Define build arguments for user, group, and Node.js version
ARG NODE_VERSION=18

# Update the package lists and install required system dependencies
RUN apt-get update --fix-missing && apt-get install -y \
    curl wget zip unzip g++ libpng-dev libonig-dev libxml2-dev libzip-dev libldap2-dev \
    gnupg2 apt-transport-https

# Clean up package cache to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions
RUN docker-php-ext-install exif intl pdo pdo_mysql ldap

# Enable the installed PHP extensions
RUN docker-php-ext-enable exif intl pdo pdo_mysql ldap

# Install Xdebug extension for debugging and enable it
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install Composer (PHP dependency manager) globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add Nodesource GPG key and repository for Node.js installation
RUN mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && npm install -g pnpm

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

RUN groupadd --force -g 1000 www
RUN useradd -ms /bin/bash --no-user-group -g 1000 -u 1337 www

# Change ownership of the storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Change permissions of the storage and cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Define the default command to run PHP-FPM
CMD ["php-fpm"]
