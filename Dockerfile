FROM adminer:5

# Métadonnées
LABEL maintainer="italic"
LABEL description="Adminer with automatic DESC sorting on primary keys"
LABEL version="2.0.0"
LABEL repository="https://github.com/germain-italic/adminer-docker-custom"

# Crée le répertoire des plugins
RUN mkdir -p /var/www/html/adminer-plugins

# Copie le plugin dans le répertoire standard
COPY plugin-desc-sort.php /var/www/html/adminer-plugins/

# Utilise USER root temporairement pour les permissions
USER root

# Crée le fichier de configuration des plugins
RUN echo '<?php return array(new AdminerDescSort);' > /var/www/html/adminer-plugins.php

# Remet les bonnes permissions
RUN chown -R www-data:www-data /var/www/html/adminer-plugins*

# Revient à l'utilisateur par défaut
USER www-data

# Expose le port 8080
EXPOSE 8080

# Variables d'environnement par défaut
ENV ADMINER_DEFAULT_SERVER=localhost