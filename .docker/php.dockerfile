# Use the PHP 8.3 CLI on Alpine Linux. It is the lowest version the package supports,
# so code relying on newer PHP features fails locally just as it would for users.
# The CLI image is enough, as the playground is served by PHP's built-in server
FROM php:8.3-cli-alpine AS base

# Accept UID and GID as build arguments to match host user permissions
ARG UID
ARG GID

# Set environment variables for UID and GID to be used in RUN commands
ENV UID=${UID}
ENV GID=${GID}

# Create the directory the package is mounted into
RUN mkdir -p /var/www/html

# Set the working directory for all subsequent commands
WORKDIR /var/www/html

# Install system dependencies and libraries the PHP extensions below link against
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    zip \
    icu-dev \
    libzip-dev \
    linux-headers

# Install PHP extensions required by Laravel and Testbench that the base image lacks.
# PDO with SQLite, mbstring, dom and the other defaults already ship with the image
RUN docker-php-ext-install \
    bcmath \
    intl \
    pcntl \
    zip

# Copy Composer from the latest official Composer image to the container
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set PHP memory limit to 512MB so Composer and the test suite never run out
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory-limit.ini

# Remove the dialout group to avoid conflicts with user creation
RUN delgroup dialout

# Create a system group named 'laravel' with the specified GID for consistent permissions
RUN addgroup -g ${GID} --system laravel

# Create a system user named 'laravel' with the specified UID, add to laravel group, set shell to /bin/sh
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

# Change ownership of the package directory to the laravel user
RUN chown -R laravel:laravel /var/www/html

# Switch to the laravel user for all subsequent operations
USER laravel

# Keep the container running so commands can be executed inside it
CMD ["tail", "-f", "/dev/null"]

# Extend the base image with a code coverage driver, built only when this stage is
# targeted so the image used for day to day development carries no coverage driver
FROM base AS coverage

# Switch back to root so the extension can be compiled and installed
USER root

# Install PCOV, discarding the build toolchain in the same layer so it is never persisted
RUN apk add --no-cache --virtual .build-deps ${PHPIZE_DEPS} \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && pecl clear-cache \
    && apk del .build-deps

# Restrict instrumentation to the package source
RUN echo "pcov.directory=/var/www/html/src" >> /usr/local/etc/php/conf.d/docker-php-ext-pcov.ini

# Switch back to the laravel user so file ownership of generated reports matches the host
USER laravel
