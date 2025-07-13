FROM adminer:latest

# Copie le plugin personnalisé
COPY custom-adminer.php /var/www/html/

# Crée un nouveau point d'entrée qui charge le plugin
# Utilise /tmp pour éviter les problèmes de permissions, puis copie
RUN echo '<?php' > /tmp/index.php && \
    echo 'function adminer_object() {' >> /tmp/index.php && \
    echo '    include_once "custom-adminer.php";' >> /tmp/index.php && \
    echo '    return new AdminerCustom;' >> /tmp/index.php && \
    echo '}' >> /tmp/index.php && \
    echo 'include "adminer.php";' >> /tmp/index.php && \
    mv /var/www/html/index.php /var/www/html/adminer.php && \
    cp /tmp/index.php /var/www/html/index.php