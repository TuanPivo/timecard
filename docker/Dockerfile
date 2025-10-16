ARG php_version

FROM php:$php_version-apache

# Copy the Apache vhost configuration
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite
RUN a2enmod rewrite

USER root

# Install system dependencies
RUN rm -rf /var/lib/apt/lists/* && mkdir -p /var/lib/apt/lists/partial && \
    apt-get clean && \
    apt-get update && \
    apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsodium-dev \
    zip \
    unzip \
    sudo \
    vim

# Install and enable Imagick
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions imagick/imagick@28f27044e435a2b203e32675e942eb8de620ee58

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sodium zip

# Cài Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"


# Command to start Apache in the foreground
CMD composer i &&\
    composer run post-root-package-install &&\
    apache2-foreground
