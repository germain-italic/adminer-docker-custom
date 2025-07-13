#!/bin/bash

# Script de setup simple pour Adminer Custom

echo "🚀 Setup Adminer Custom..."

# Arrête l'ancien container adminer
if docker ps -q -f name=adminer | grep -q .; then
    echo "🛑 Arrêt de l'ancien container adminer..."
    docker stop adminer
    docker rm adminer
fi

# Copie .env si il n'existe pas
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ Fichier .env créé"
else
    echo "ℹ️  Fichier .env existe déjà"
fi

# Build et start
echo "🔨 Construction de l'image..."
docker-compose build

echo "🚀 Démarrage du service..."
docker-compose up -d

echo "✅ Adminer Custom démarré !"
echo "📍 Accès: http://localhost:8081"