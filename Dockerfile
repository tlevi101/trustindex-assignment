FROM php:8.4-cli-alpine

# Install PHP extensions via the extension installer (handles system deps)
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
        pdo_pgsql \
        intl \
        opcache \
        zip

# Composer (copied from the official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install dependencies first to leverage Docker layer caching
COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-scripts --no-interaction --prefer-dist

# Copy the rest of the application
COPY . .
RUN composer dump-autoload --optimize

EXPOSE 8000

# Serve via PHP's built-in web server (no Symfony CLI needed)
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
