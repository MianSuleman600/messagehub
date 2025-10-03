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
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip bcmath

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Install Node dependencies (Tailwind CLI + PostCSS)
RUN npm install tailwindcss postcss autoprefixer

# Build Tailwind CSS
RUN npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --minify

# Optional: build JS if using Vite
RUN npm install && npm run build

# Set permissions for storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache public/css

# Expose port 8080
EXPOSE 8080

# Run migrations on container start and then start Laravel server
CMD php artisan migrate --force && php artisan serve --host 0.0.0.0 --port 8080
 