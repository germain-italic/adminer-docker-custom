FROM adminer:5

# Metadata
LABEL maintainer="italic"
LABEL description="Adminer with automatic DESC sorting on primary keys"
LABEL version="2.0.0"
LABEL repository="https://github.com/germain-italic/adminer-docker-custom"

# Copy plugin to plugins-enabled with wrapper
COPY adminer-plugins/desc-sort-plugin.php /var/www/html/plugins-enabled/desc-sort.php

# Create wrapper that returns plugin instance
RUN echo '<?php\nrequire_once(__DIR__ . "/desc-sort.php");\nreturn new AdminerDescSort();' > /var/www/html/plugins-enabled/desc-sort-wrapper.php && \
    mv /var/www/html/plugins-enabled/desc-sort.php /var/www/html/plugins-enabled/desc-sort-class.php && \
    mv /var/www/html/plugins-enabled/desc-sort-wrapper.php /var/www/html/plugins-enabled/desc-sort.php

# Set permissions
USER root
RUN chown -R www-data:www-data /var/www/html/plugins-enabled/
USER www-data

# Expose port 8080
EXPOSE 8080

# Default environment variables
ENV ADMINER_DEFAULT_SERVER=localhost