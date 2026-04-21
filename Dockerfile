FROM php:8.4-apache

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip unzip git curl libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Apache rewrite enable
RUN a2enmod rewrite

# Fix document root to Laravel public folder
ENV APACHE_DOCUMENT_ROOT /var/www/public

RUN sed -ri -e 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!/var/www/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www

EXPOSE 80

CMD ["apache2-foreground"]
