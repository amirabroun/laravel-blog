FROM php:8-fpm

USER root
RUN apt-get update && apt-get install -y nodejs npm

RUN apt-get update --fix-missing \
    && apt-get install -y curl wget zip unzip git g++ \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /usr/share/nginx/www 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug \ docker-php-ext-enable xdebug 

COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY --chown=www:www . /

USER www

CMD ["php-fpm"]