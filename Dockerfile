FROM php:8.2-apache

#les dependances système
RUN apt-get update && apt-get install -y \
    libicu-dev libonig-dev libzip-dev zip unzip git curl gnupg2 libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql opcache zip \
    && pecl install mongodb && docker-php-ext-enable mongodb \
    && a2enmod rewrite

# Configuration Apache
COPY ./apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# copier le projet dans /var/www/html
WORKDIR /var/www/html
COPY . .

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Permissions (donner les droits d'accès)
RUN chown -R www-data:www-data /var/www/html
