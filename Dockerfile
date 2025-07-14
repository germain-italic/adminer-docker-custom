FROM adminer:5

# Metadata
LABEL maintainer="italic"
LABEL description="Adminer with automatic DESC sorting on primary keys"
LABEL version="2.0.0"
LABEL repository="https://github.com/germain-italic/adminer-docker-custom"

# Create plugins directory
RUN mkdir -p /var/www/html/adminer-plugins

# Copy plugin directly to plugins directory (not in subdirectory)
COPY adminer-plugins/desc-sort-plugin.php /var/www/html/adminer-plugins/desc-sort.php

# Use root temporarily for permissions
USER root

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/adminer-plugins/

# Return to default user
USER www-data

# Expose port 8080
EXPOSE 8080

# Default environment variables
ENV ADMINER_DEFAULT_SERVER=localhost