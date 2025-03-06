FROM php:8.4-fpm

RUN apt-get update && apt-get install -y libicu-dev git unzip libpng-dev libjpeg-dev libfreetype6-dev && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY . /var/www

RUN chown -R www-data:www-data /var/www

RUN composer install --no-dev --optimize-autoloader

EXPOSE 9000
