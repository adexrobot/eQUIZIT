# Stage 1: Build Node assets with Vite
FROM node:18-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Stage 2: PHP Application
FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    gd \
    zip \
    pdo \
    pdo_mysql \
    bcmath \
    ctype \
    fileinfo \
    json \
    mbstring \
    openssl \
    tokenizer \
    xml

# Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod headers

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel application
COPY . .

# Copy built assets from node builder
COPY --from=node-builder /app/public/build ./public/build

# Install composer dependencies
RUN composer install --no-dev --no-interaction --prefer-dist

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/bootstrap/cache

# Configure Apache
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Expose port
EXPOSE 80

# Run migrations and start Apache
CMD ["sh", "-c", "php artisan migrate --force && apache2-foreground"]
