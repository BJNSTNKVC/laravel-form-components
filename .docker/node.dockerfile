# Use Node.js 26, the current LTS release, on Alpine Linux for smaller size
FROM node:26-alpine

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

# Install Alpine packages needed by npm dependencies that compile native code
RUN apk add --no-cache \
    curl \
    git \
    python3 \
    make \
    g++

# Remove the image's own 'node' user and group, which hold UID and GID 1000
# and would otherwise clash with the user created below
RUN deluser --remove-home node && (delgroup node 2>/dev/null || true)

# Remove the dialout group to avoid conflicts with user creation
RUN delgroup dialout

# Create a system group named 'laravel' with the specified GID for consistent permissions
RUN addgroup -g ${GID} --system laravel

# Create a system user named 'laravel' with the specified UID, add to laravel group, set shell to /bin/sh
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

# Set npm cache to be writable
RUN npm config set cache /tmp/.npm --global

# Change ownership of the package directory to the laravel user
RUN chown -R laravel:laravel /var/www/html

# Switch to the laravel user for all subsequent operations
USER laravel

# Keep the container running until there are assets to build
CMD ["tail", "-f", "/dev/null"]
