FROM adminer:4.8.1

# Métadonnées
LABEL maintainer="italic"
LABEL description="Adminer with automatic DESC sorting on primary keys"
LABEL version="1.0.0"
LABEL repository="https://github.com/germain-italic/adminer-docker-custom"

# Copie le fichier principal qui remplace index.php
COPY custom-adminer.php /var/www/html/index.php

# Expose le port 8080 (port standard d'Adminer)
)
EXPOSE 8080

# Variables d'environnement par défaut
ENV ADMINER_DEFAULT_SERVER=localhost