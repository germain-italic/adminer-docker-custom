FROM adminer:latest

# Copie le plugin personnalisé
COPY custom-adminer.php /var/www/html/

# Modifie le fichier index.php pour inclure notre plugin
# Insère l'include après la déclaration de namespace (ligne 2)
RUN sed -i '2a include_once "custom-adminer.php";' /var/www/html/index.php && \
    sed -i 's/return new Adminer;/return new AdminerPlugin(array(new AdminerCustomSort));/' /var/www/html/index.php