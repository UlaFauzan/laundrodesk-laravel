FROM php:8.2-apache

# Enable required PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    postgresql-client \
    git \
    curl \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy application
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache
RUN echo '<Directory /var/www/html/public>' >> /etc/apache2/apache2.conf && \
    echo '  Options Indexes FollowSymLinks' >> /etc/apache2/apache2.conf && \
    echo '  AllowOverride All' >> /etc/apache2/apache2.conf && \
    echo '  Require all granted' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf

RUN a2dissite 000-default
RUN echo '<VirtualHost *:80>' > /etc/apache2/sites-available/laravel.conf && \
    echo 'DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/laravel.conf && \
    echo '<Directory /var/www/html>' >> /etc/apache2/sites-available/laravel.conf && \
    echo 'AllowOverride All' >> /etc/apache2/sites-available/laravel.conf && \
    echo '</Directory>' >> /etc/apache2/sites-available/laravel.conf && \
    echo '</VirtualHost>' >> /etc/apache2/sites-available/laravel.conf

RUN a2ensite laravel.conf

# Run migrations and optimization
RUN php artisan cache:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 80

CMD ["apache2-foreground"]
