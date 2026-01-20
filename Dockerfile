# Base image with Apache and PHP
FROM php:8.2-apache

# Install PHP extensions required by the app (PDO + mysqli)
RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev zlib1g-dev libpng-dev libjpeg-dev \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql mysqli zip gd \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers

# Configure Apache DirectoryIndex and DocumentRoot
RUN echo 'DirectoryIndex index.html index.php' > /etc/apache2/mods-enabled/dir.conf

# Set DocumentRoot to /var/www/html/public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-enabled/000-default.conf \
    && sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/public>|g' /etc/apache2/sites-enabled/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . /var/www/html

# Ensure storage directories exist and have correct permissions
RUN mkdir -p /var/www/html/storage /var/www/html/storage/notes /var/www/html/storage/photos \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/public /var/www/html/includes \
    && chmod -R 755 /var/www/html/storage

# Expose port 80
EXPOSE 80

# Start Apache in foreground (default for image)
CMD ["apache2-foreground"]
