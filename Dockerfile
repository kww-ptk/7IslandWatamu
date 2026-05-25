FROM php:8.2-apache

# Install PostgreSQL PDO extension
RUN apt-get update && apt-get install -y libpq-dev postgresql-client libgd-dev libjpeg62-turbo-dev libpng-dev libwebp-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_pgsql gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Allow .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

# Give Apache write access to upload and log directories
RUN chown -R www-data:www-data /var/www/html/assets/img/rooms \
    && chown -R www-data:www-data /var/www/html/logs \
    && chmod -R 775 /var/www/html/assets/img/rooms \
    && chmod -R 775 /var/www/html/logs
