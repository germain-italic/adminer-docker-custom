FROM adminer:latest

# Copie le fichier principal qui remplace index.php
COPY custom-adminer.php /var/www/html/index.php