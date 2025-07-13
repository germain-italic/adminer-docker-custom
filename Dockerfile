FROM adminer:5.3.0

# Métadonnées
LABEL maintainer="italic"
LABEL description="Adminer with automatic DESC sorting on primary keys"
LABEL version="1.0.0"
LABEL repository="https://github.com/germain-italic/adminer-docker-custom"

# Copie le plugin universel
COPY plugin-desc-sort.php /var/www/html/plugin-desc-sort.php

# Crée un index.php qui inclut le plugin
RUN echo '<?php include "plugin-desc-sort.php"; include "adminer.php";' > /var/www/html/index.php

# Expose le port 8080
EXPOSE 8080

# Variables d'environnement par défaut
ENV ADMINER_DEFAULT_SERVER=localhost