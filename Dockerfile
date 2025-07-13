FROM adminer:latest

# Copie le plugin comme fichier séparé
COPY custom-adminer.php /var/www/html/plugin.php

# Modifie l'index pour inclure le plugin
RUN echo '<?php $plugins = array(); include "plugin.php"; include "index.php"; ?>' > /tmp/new_index.php && \
    mv /var/www/html/index.php /var/www/html/adminer.php && \
    mv /tmp/new_index.php /var/www/html/index.php