FROM adminer:latest

# Copie le plugin personnalisé dans le dossier plugins-enabled
COPY custom-adminer.php /var/www/html/plugins-enabled/