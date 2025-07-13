FROM adminer:latest

# Copie le plugin personnalisé
COPY custom-adminer.php /var/www/html/

# Crée un nouveau point d'entrée qui charge le plugin
RUN echo '<?php' > /var/www/html/index.php && \
    echo 'function adminer_object() {' >> /var/www/html/index.php && \
    echo '    include_once "custom-adminer.php";' >> /var/www/html/index.php && \
    echo '    return new AdminerCustom;' >> /var/www/html/index.php && \
    echo '}' >> /var/www/html/index.php && \
    echo 'include "adminer.php";' >> /var/www/html/index.php