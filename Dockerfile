# Use the official PHP 8.3 image with FPM (FastCGI Process Manager)
FROM php:8.3-fpm

# Define build arguments for user, group, and Node.js version
ARG WWWUSER=1000
ARG WWWGROUP=1000
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

# Set the working directory for the application
WORKDIR /usr/share/nginx/www

# Copy application files from the host machine to the container
COPY . .

# Ensure proper permissions for storage and cache directories
RUN mkdir -p /storage /bootstrap/cache && \
    chown -R root:root /storage /bootstrap/cache && \
    chmod -R 777 /storage /bootstrap/cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Define the default command to run PHP-FPM
CMD ["php-fpm"]
