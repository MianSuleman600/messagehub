# -------------------------
# Base Image
# -------------------------
FROM php:8.2-fpm

# -------------------------
# System dependencies
# -------------------------
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

# -------------------------
# Set working directory
# -------------------------
WORKDIR /var/www/html

# -------------------------
# Install Composer
# -------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -------------------------
# Copy composer files and install PHP dependencies
# -------------------------
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev

# -------------------------
# Copy the rest of the application
# -------------------------
COPY . .

# -------------------------
# Install Node dependencies for Vite
# -------------------------
COPY package.json package-lock.json vite.config.js tailwind.config.js postcss.config.js ./
RUN npm install

# -------------------------
# Build frontend assets
# -------------------------
RUN npm run build

# -------------------------
# Generate Laravel key
# -------------------------
RUN php artisan key:generate

# -------------------------
# Set permissions
# -------------------------
RUN chown -R www-data:www-data storage bootstrap/cache

# -------------------------
# Expose port 8080
# -------------------------
EXPOSE 8080

# -------------------------
# Start Laravel server
# -------------------------
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
