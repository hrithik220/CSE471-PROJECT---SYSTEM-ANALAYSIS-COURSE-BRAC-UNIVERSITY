FROM php:8.4-apache

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip unzip git curl \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

COPY . .

# Composer install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www

EXPOSE 80

CMD ["apache2-foreground"]
