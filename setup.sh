#!/bin/bash

# Script de setup pour Adminer Custom avec tri DESC par défaut

echo "🚀 Setup Adminer Custom - Tri DESC par défaut..."
echo ""

# Vérification Docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker n'est pas installé"
    echo "   Installez Docker : https://docs.docker.com/get-docker/"
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose n'est pas installé"
    echo "   Installez Docker Compose : https://docs.docker.com/compose/install/"
    exit 1
fi

# Arrête l'ancien container s'il existe
if docker ps -q -f name=adminer-custom | grep -q .; then
    echo "🛑 Arrêt de l'ancien container adminer-custom..."
    docker stop adminer-custom
    docker rm adminer-custom
fi

# Création du réseau Docker si nécessaire
if ! docker network ls | grep -q adminer-network; then
    echo "🌐 Création du réseau Docker..."
    docker network create adminer-network
else
    echo "ℹ️  Réseau Docker adminer-network existe déjà"
fi

# Copie .env si il n'existe pas
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ Fichier .env créé avec la configuration par défaut"
else
    echo "ℹ️  Fichier .env existe déjà"
fi

# Affiche la configuration
echo ""
echo "📋 Configuration actuelle :"
if [ -f .env ]; then
    cat .env | grep -v "^#" | grep -v "^$"
else
    echo "   Port: 8081 (par défaut)"
fi

echo ""
echo "🔨 Construction de l'image Docker..."
docker-compose build

echo ""
echo "🚀 Démarrage du service..."
docker-compose up -d

# Vérification que le container fonctionne
sleep 3
if docker ps | grep -q adminer-custom; then
    echo ""
    echo "✅ Adminer Custom démarré avec succès !"
    echo ""
    echo "📍 Accès: http://localhost:$(grep ADMINER_PORT .env 2>/dev/null | cut -d'=' -f2 || echo 8081)"
    echo "🎯 Fonctionnalité: Tri DESC automatique sur la colonne 'id'"
    echo ""
    echo "💡 Commandes utiles :"
    echo "   docker-compose logs -f     # Voir les logs"
    echo "   docker-compose restart     # Redémarrer"
    echo "   docker-compose down        # Arrêter"
    echo ""
else
    echo ""
    echo "❌ Erreur lors du démarrage"
    echo "   Vérifiez les logs : docker-compose logs"
    exit 1
fi