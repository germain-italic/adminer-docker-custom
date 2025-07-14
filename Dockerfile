FROM adminer:5

# Metadata
LABEL maintainer="italic"
LABEL description="Adminer with automatic DESC sorting on primary keys"
LABEL version="2.0.0"
LABEL repository="https://github.com/germain-italic/adminer-docker-custom"

# Create plugins-enabled directory and copy plugin
RUN mkdir -p /var/www/html/plugins-enabled
COPY adminer-plugins/desc-sort-plugin.php /var/www/html/adminer-plugins/desc-sort-plugin.php

# Create wrapper file that returns plugin instance
RUN echo '<?php require_once(__DIR__ . "/../adminer-plugins/desc-sort-plugin.php"); return new AdminerDescSort();' > /var/www/html/plugins-enabled/desc-sort.php

# Set permissions
USER root
RUN chown -R www-data:www-data /var/www/html/
USER www-data

# Expose port 8080
EXPOSE 8080

# Default environment variables
ENV ADMINER_DEFAULT_SERVER=localhost