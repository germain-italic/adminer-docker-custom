# Adminer Custom - Default DESC Sort

Adminer avec tri automatique DESC sur les clés primaires par défaut.

## 🚀 Installation rapide

### Option 1: Docker Hub (Recommandé)

```bash
# Lancement direct
docker run -p 8081:8080 italic/adminer-desc-sort

# Ou avec docker-compose
curl -O https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/docker-compose.hub.yml
docker-compose -f docker-compose.hub.yml up -d
```

### Option 2: Installation manuelle

```bash
# 1. Télécharger le plugin
wget https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/plugin-desc-sort.php

# 2. Créer le répertoire des plugins
mkdir -p adminer-plugins

# 3. Déplacer le plugin
mv plugin-desc-sort.php adminer-plugins/

# 4. Créer le fichier de configuration
echo '<?php return array(new AdminerDescSort);' > adminer-plugins.php
```

### Option 3: Build depuis les sources

```bash
git clone https://github.com/germain-italic/adminer-docker-custom.git
cd adminer-docker-custom
chmod +x setup.sh
./setup.sh
```

## ✨ Fonctionnalités

- ✅ Tri automatique **DESC** sur la colonne `id` par défaut
- ✅ Compatible avec l'architecture standard des plugins Adminer
- ✅ Basé sur Adminer 5.x (toujours la dernière version stable)
- ✅ Aucune modification d'Adminer requise

## 🔧 Comment ça marche

Le plugin utilise l'architecture standard d'Adminer :
- Se place dans `adminer-plugins/`
- Se charge via `adminer-plugins.php`
- Modifie automatiquement les requêtes SELECT pour ajouter `ORDER BY id DESC`

## 🌐 Accès

- **URL par défaut** : http://localhost:8081
- **Port** : Configurable via la variable `ADMINER_PORT`

## ⚙️ Configuration

### Variables d'environnement

```bash
# Port (défaut: 8081)
ADMINER_PORT=8081

# Serveur de base de données par défaut (optionnel)
DB_HOST=localhost
```

### Port personnalisé

```bash
# Docker
docker run -p 8082:8080 italic/adminer-desc-sort

# Docker Compose
echo "ADMINER_PORT=8082" > .env
docker-compose up -d
```

## 🛠️ Développement

### Build local

```bash
docker build -t italic/adminer-desc-sort:local .
docker run -p 8081:8080 italic/adminer-desc-sort:local
```

## 🧪 Test

1. Démarrer Adminer : `docker run -p 8081:8080 italic/adminer-desc-sort`
2. Ouvrir : http://localhost:8081
3. Se connecter à votre base de données
4. Sélectionner une table
5. Vérifier : Les données sont triées DESC sur la colonne `id` par défaut !

## 📁 Structure du projet

```
├── docker-compose.yml        # Build local
├── docker-compose.hub.yml    # Image Docker Hub
├── Dockerfile               # Construction de l'image
├── plugin-desc-sort.php     # Plugin Adminer
├── setup.sh                # Script d'installation automatique
└── README.md               # Ce fichier
```

## 📄 Licence

Apache License 2.0 (identique à Adminer)

## 🔗 Liens

- **GitHub** : https://github.com/germain-italic/adminer-docker-custom
- **Docker Hub** : https://hub.docker.com/r/italic/adminer-desc-sort
- **Issues** : https://github.com/germain-italic/adminer-docker-custom/issues