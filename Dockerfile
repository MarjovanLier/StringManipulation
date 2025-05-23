FROM php:8.3-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    zip \
    unzip \
    bash \
    curl \
    libzip-dev \
    linux-headers \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-install zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configure Xdebug for coverage
RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files first for better caching
COPY composer.json composer.lock* ./

# Install dependencies
RUN composer install --no-interaction --no-progress --prefer-dist --optimize-autoloader

# Copy the rest of the application
COPY . .

# Create a non-root user to run tests
RUN adduser -D -u 1000 testuser && chown -R testuser:testuser /app
USER testuser

# Default command
CMD ["composer", "tests"]
