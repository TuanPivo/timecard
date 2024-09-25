ARG php_version

FROM php:$php_version-apache

USER root

# Arguments defined in docker-compose.yml
ARG user
ARG uid

COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev libzip4 \
    libsodium-dev \
    zip \
    unzip \
    sudo \
    vim

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sodium zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

USER $user