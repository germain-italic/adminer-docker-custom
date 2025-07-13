# Installation du plugin DESC Sort pour Adminer

## 🎯 Plugin universel

Ce plugin fonctionne avec **toutes** les installations d'Adminer :
- ✅ Docker
- ✅ Installation vanilla (Apache, Nginx, etc.)
- ✅ XAMPP, WAMP, MAMP
- ✅ Serveur dédié

## 🚀 Installation universelle

### Méthode 1: Plugin séparé (Recommandée)

```bash
# 1. Télécharger le plugin universel
wget https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/plugin-desc-sort.php

# 2. Inclure au début de votre index.php
```

Modifier votre `index.php` existant :

```php
<?php
// Inclure le plugin DESC Sort
include 'plugin-desc-sort.php';

// Votre code Adminer existant...
include 'adminer.php'; // ou votre fichier Adminer
```

### Méthode 2: Remplacement complet

```bash
# 1. Sauvegarder l'existant
cp index.php index.php.backup

# 2. Télécharger la version complète
wget https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/adminer-with-desc-sort.php
mv adminer-with-desc-sort.php index.php
```

### Méthode 3: Modification manuelle

Ajouter ce code au **début** de votre `index.php` :

```php
<?php
// Plugin DESC Sort
if (!isset($_GET["order"]) && isset($_GET["select"])) {
    if (!headers_sent()) {
        $current_url = $_SERVER['REQUEST_URI'];
        if (strpos($current_url, 'order') === false) {
            $separator = strpos($current_url, '?') !== false ? '&' : '?';
            $new_url = $current_url . $separator . 'order%5B0%5D=id&desc%5B0%5D=1';
            header("Location: $new_url");
            exit;
        }
    }
}

// Votre code Adminer existant...
```

## 🐳 Installation Docker

```bash
# Utiliser l'image Docker Hub
docker run -p 8081:8080 italic/adminer-desc-sort

# Ou construire localement
git clone https://github.com/germain-italic/adminer-docker-custom.git
cd adminer-docker-custom
docker-compose up -d
```

## 🧪 Test

1. **Accéder** à votre Adminer
2. **Se connecter** à une base de données  
3. **Sélectionner** une table
4. **Vérifier** que les données sont triées en DESC sur `id`

## 📋 Compatibilité

- ✅ Adminer 4.8.x
- ✅ PHP 7.4+
- ✅ Toutes bases de données
- ✅ Apache, Nginx, IIS
- ✅ Docker, Kubernetes
- ✅ XAMPP, WAMP, MAMP

## 🔄 Désinstallation

### Plugin séparé
```bash
# Supprimer la ligne include du index.php
sed -i '/plugin-desc-sort.php/d' index.php
rm plugin-desc-sort.php
```

### Remplacement complet
```bash
# Restaurer la sauvegarde
cp index.php.backup index.php
```

## 🆘 Dépannage

### Plugin ne fonctionne pas
- ✅ Vérifier que le code est au **début** du fichier
- ✅ Vérifier les permissions PHP (`chmod 644`)
- ✅ Vérifier les logs d'erreur du serveur web

### Conflit avec d'autres plugins
- ✅ Installer en dernier
- ✅ Vérifier l'ordre d'inclusion

### Erreur "headers already sent"
- ✅ S'assurer qu'il n'y a pas d'espaces avant `<?php`
- ✅ Vérifier l'encodage du fichier (UTF-8 sans BOM)

### Restaurer l'original
```bash
cp index.php.backup index.php
```

## 💡 Exemples d'installation

### XAMPP (Windows)
```bash
# Copier dans le dossier Adminer de XAMPP
copy plugin-desc-sort.php C:\xampp\htdocs\adminer\
# Modifier C:\xampp\htdocs\adminer\index.php
```

### Ubuntu/Debian
```bash
# Installation dans /var/www/html/adminer/
sudo wget -O /var/www/html/adminer/plugin-desc-sort.php \
  https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/plugin-desc-sort.php
```

### CentOS/RHEL
```bash
# Installation dans /var/www/html/adminer/
sudo curl -o /var/www/html/adminer/plugin-desc-sort.php \
  https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/plugin-desc-sort.php
```

## 🎯 Un seul plugin, toutes les installations !

Le même fichier `plugin-desc-sort.php` fonctionne partout ! 🚀