FROM php:8.3-fpm as books-app

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Install pdo_mysql extension
RUN docker-php-ext-install pdo pdo_mysql

# Install XDebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /srv/app/

# Copy composer files and install dependencies (for caching)
COPY composer.json composer.lock /srv/app/

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy the rest of the app
COPY . /srv/app/

# Set permissions (optional, depending on your needs)
RUN chown -R www-data:www-data /srv/app/