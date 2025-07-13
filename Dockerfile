FROM adminer:latest

# Copie le plugin personnalisé
COPY custom-adminer.php /var/www/html/

# Modifie directement le fichier index.php existant
RUN sed -i '1i<?php include_once "custom-adminer.php"; ?>' /var/www/html/index.php && \
    sed -i 's/return new Adminer;/return new AdminerPlugin(array(new AdminerCustomSort));/' /var/www/html/index.php