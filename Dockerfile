FROM php:8.2-apache

# Install PostgreSQL dev libraries & PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy project files to Apache web root
COPY . /var/www/html/

# Adjust permissions for runtime & web root
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 777 /var/www/html/runtime /var/www/html/runtime_sessions /var/www/html/uploads

# Configure Apache DocumentRoot to web root
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

EXPOSE 80
