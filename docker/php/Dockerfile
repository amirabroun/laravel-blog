FROM php:8.2.2-fpm

RUN apt-get update --fix-missing && apt-get install -y curl wget zip unzip git g++ \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    npm \
    nano \
    nodejs

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN set -e docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype docker-php-ext-install gd

RUN docker-php-ext-install exif

RUN docker-php-ext-install intl

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /usr/share/nginx/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug \ docker-php-ext-enable xdebug

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY --chown=www:www . /

USER www

CMD ["php-fpm"]
