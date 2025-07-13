FROM adminer:latest

# Copie le plugin personnalisé
COPY custom-adminer.php /var/www/html/

# Crée un nouveau point d'entrée qui charge le plugin
RUN echo '<?php' > /tmp/index.php && \
    echo 'function adminer_object() {' >> /tmp/index.php && \
    echo '    include_once "custom-adminer.php";' >> /tmp/index.php && \
    echo '    $plugins = array(new AdminerCustomSort);' >> /tmp/index.php && \
    echo '    return new AdminerPlugin($plugins);' >> /tmp/index.php && \
    echo '}' >> /tmp/index.php && \
    echo 'include "adminer.php";' >> /tmp/index.php && \
    mv /var/www/html/index.php /var/www/html/adminer.php && \
    cp /tmp/index.php /var/www/html/index.php