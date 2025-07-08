FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    default-mysql-client \
    zip \
    unzip \
    git \
    curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy package files first for better Docker layer caching
COPY package*.json ./

# Install Node.js dependencies (for development)
RUN npm install

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 755 /var/www/html/public/uploads \
    && chmod -R 755 /var/www/html/storage

COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]