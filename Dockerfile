# Use PHP 8.2 FPM base image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    curl \
    zip \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath

# Set working directory
WORKDIR /var/www/html

# Copy composer files first
COPY composer.lock composer.json ./

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Copy all project files
COPY . .

# Generate application key
RUN php artisan key:generate

# Set permissions for storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 8080
EXPOSE 8080

# Start Laravel development server
CMD php artisan serve --host 0.0.0.0 --port 8080
