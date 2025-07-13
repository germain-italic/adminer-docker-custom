#!/bin/bash

# Setup script for Adminer Custom with default DESC sort

echo "🚀 Setting up Adminer Custom - Default DESC sort..."
echo ""

# Check Docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed"
    echo "   Install Docker: https://docs.docker.com/get-docker/"
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed"
    echo "   Install Docker Compose: https://docs.docker.com/compose/install/"
    exit 1
fi

# Stop old container if exists
if docker ps -q -f name=adminer-custom | grep -q .; then
    echo "🛑 Stopping old adminer-custom container..."
    docker stop adminer-custom
    docker rm adminer-custom
fi

# Create Docker network if needed
if ! docker network ls | grep -q adminer-network; then
    echo "🌐 Creating Docker network..."
    docker network create adminer-network
else
    echo "ℹ️  Docker network adminer-network already exists"
fi

# Copy .env if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ .env file created with default configuration"
else
    echo "ℹ️  .env file already exists"
fi

# Display current configuration
echo ""
echo "📋 Current configuration:"
if [ -f .env ]; then
    cat .env | grep -v "^#" | grep -v "^$"
else
    echo "   Port: 8081 (default)"
fi

echo ""
echo "🔨 Building Docker image..."
docker-compose build

echo ""
echo "🚀 Starting service..."
docker-compose up -d

# Check if container is running
sleep 3
if docker ps | grep -q adminer-custom; then
    echo ""
    echo "✅ Adminer Custom started successfully!"
    echo ""
    echo "📍 Access: http://localhost:$(grep ADMINER_PORT .env 2>/dev/null | cut -d'=' -f2 || echo 8081)"
    echo "🎯 Feature: Automatic DESC sort on 'id' column"
    echo ""
    echo "💡 Useful commands:"
    echo "   docker-compose logs -f     # View logs"
    echo "   docker-compose restart     # Restart"
    echo "   docker-compose down        # Stop"
    echo ""
    echo "🔗 Repository: https://github.com/germain-italic/adminer-docker-custom"
    echo ""
else
    echo ""
    echo "❌ Error during startup"
    echo "   Check logs: docker-compose logs"
    exit 1
fi