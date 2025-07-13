# Adminer Custom - Tri DESC par défaut

Adminer avec tri DESC par défaut sur les clés primaires.

## Fonctionnalités

- ✅ Tri automatique en **DESC** sur la colonne `id` par défaut
- ✅ Basé sur Adminer 5.3.0 (dernière version stable)
- ✅ Configuration Docker simple
- ✅ Redirection automatique pour forcer l'ordre DESC

## Installation rapide

```bash
# Cloner ou télécharger ce projet
git clone <votre-repo>
cd adminer-custom

# Setup automatique
chmod +x setup.sh
./setup.sh
```

## Installation manuelle

```bash
# 1. Copier la configuration
cp .env.example .env

# 2. Modifier le port si nécessaire (optionnel)
nano .env

# 3. Construire et démarrer
docker-compose up -d --build
```

## Accès

- **URL** : http://localhost:8081
- **Port par défaut** : 8081 (configurable dans `.env`)

## Configuration

### Variables d'environnement (`.env`)

```bash
# Port d'écoute d'Adminer
ADMINER_PORT=8081

# Serveur de base de données par défaut (optionnel)
DB_HOST=localhost
```

### Personnalisation du port

```bash
# Modifier le port dans .env
echo "ADMINER_PORT=8082" > .env

# Redémarrer
docker-compose down
docker-compose up -d
```

## Comment ça marche

Le plugin intercepte les requêtes de sélection et :

1. **Détecte** si aucun ordre n'est spécifié dans l'URL
2. **Redirige** automatiquement avec `order[0]=id&desc[0]=1`
3. **Force** le tri DESC sur la colonne `id` par défaut

### Code du plugin

```php
// Force DESC par défaut si aucun ordre spécifié
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
```

## Commandes utiles

```bash
# Voir les logs
docker-compose logs -f

# Redémarrer
docker-compose restart

# Arrêter
docker-compose down

# Reconstruire
docker-compose up -d --build

# Nettoyer
docker-compose down -v
docker rmi adminer-custom_adminer
```

## Résolution de problèmes

### Port déjà utilisé

```bash
# Changer le port dans .env
ADMINER_PORT=8082

# Redémarrer
docker-compose down && docker-compose up -d
```

### Problème de réseau Docker

```bash
# Créer le réseau si nécessaire
docker network create adminer-network

# Redémarrer
docker-compose up -d
```

### Réinitialiser complètement

```bash
# Tout nettoyer
docker-compose down -v
docker rmi adminer-custom_adminer
docker system prune -f

# Relancer
docker-compose up -d --build
```

## Structure du projet

```
adminer-custom/
├── docker-compose.yml    # Configuration Docker
├── Dockerfile           # Image personnalisée
├── custom-adminer.php   # Plugin de tri DESC
├── setup.sh            # Script d'installation
├── .env.example        # Configuration exemple
├── .env                # Configuration locale
└── README.md           # Documentation
```

## Compatibilité

- ✅ Adminer 5.3.0
- ✅ PHP 8.x
- ✅ Docker & Docker Compose
- ✅ Toutes bases de données supportées par Adminer

## Licence

Même licence qu'Adminer (Apache License 2.0)