# Use official PHP image
FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Set the working directory
WORKDIR /var/www

# Copy the current directory contents into the container
COPY . /var/www

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Expose port 80 for the server
EXPOSE 80

# Start the PHP FPM server
CMD ["php-fpm"]
