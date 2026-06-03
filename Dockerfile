FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    mysql-client \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate app key and cache
RUN php artisan optimize

# Set permissions
RUN chown -R www-data:www-data /app

# Expose port
EXPOSE 8000

# Start PHP
CMD ["php-fpm"]
