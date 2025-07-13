# Installation du plugin DESC Sort pour Adminer existant

## 🎯 Options d'installation

### Option 1: Remplacement simple (Recommandée)

1. **Télécharger** le fichier `adminer-with-desc-sort.php`
2. **Sauvegarder** votre `index.php` actuel
3. **Remplacer** `index.php` par `adminer-with-desc-sort.php`

```bash
# Sauvegarde
cp index.php index.php.backup

# Installation
wget https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/adminer-with-desc-sort.php
mv adminer-with-desc-sort.php index.php
```

### Option 2: Plugin séparé

1. **Télécharger** `plugin-desc-sort.php`
2. **Inclure** au début de votre `index.php`

```bash
# Télécharger le plugin
wget https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/plugin-desc-sort.php

# Modifier votre index.php
echo "<?php include 'plugin-desc-sort.php'; ?>" > temp.php
cat index.php >> temp.php
mv temp.php index.php
```

### Option 3: Modification manuelle

Ajouter ce code au **début** de votre `index.php` existant :

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

## 🧪 Test

1. **Accéder** à votre Adminer
2. **Se connecter** à une base de données
3. **Sélectionner** une table
4. **Vérifier** que les données sont triées en DESC sur `id`

## 🔄 Désinstallation

```bash
# Restaurer la sauvegarde
cp index.php.backup index.php
```

## 📋 Compatibilité

- ✅ Adminer 4.8.x
- ✅ PHP 7.4+
- ✅ Toutes bases de données
- ✅ Apache, Nginx, etc.

## 🆘 Dépannage

### Plugin ne fonctionne pas
- Vérifier que le code est au **début** du fichier
- Vérifier les permissions PHP
- Vérifier les logs d'erreur

### Conflit avec d'autres plugins
- Installer en dernier
- Vérifier l'ordre d'inclusion

### Restaurer l'original
```bash
cp index.php.backup index.php
```