# Adminer Custom - Default DESC Sort

Adminer with automatic DESC sorting on primary keys by default.

## 🚀 Quick Start

### Option 1: Docker Hub (Recommended)

```bash
# Run directly
docker run -p 8081:8080 italic/adminer-desc-sort

# Or with docker-compose
curl -O https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/docker-compose.hub.yml
docker-compose -f docker-compose.hub.yml up -d
```

### Option 2: Existing Adminer Installation

```bash
# Download the plugin
# For PHP 7+ (recommended)
wget https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/plugin-desc-sort.php

# For PHP 5.6 (legacy)
wget https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/plugin-desc-sort-php56.php
mv plugin-desc-sort-php56.php plugin-desc-sort.php

# Then include it
echo '<?php include "plugin-desc-sort.php"; ?>' | cat - index.php > temp && mv temp index.php
```

### Option 3: Build from Source

```bash
git clone https://github.com/germain-italic/adminer-docker-custom.git
cd adminer-docker-custom
chmod +x setup.sh
./setup.sh
## ✨ Features

- ✅ Automatic **DESC** sorting on `id` column by default
- ✅ Works with Docker and vanilla installations
- ✅ Based on Adminer 5.x (always latest stable)
- ✅ Universal plugin - one file for all setups

## 🔧 How it Works

The plugin automatically redirects table selections to include `order[0]=id&desc[0]=1` when no sorting is specified.

## 📦 Docker Hub

- **Image**: `italic/adminer-desc-sort`
- **URL**: https://hub.docker.com/r/italic/adminer-desc-sort

## 🌐 Access

- **Default URL**: http://localhost:8081
- **Port**: Configurable via `ADMINER_PORT` environment variable

## ⚙️ Configuration

### Environment Variables

```bash
# Port (default: 8081)
ADMINER_PORT=8081

# Default database server (optional)
DB_HOST=localhost
```

### Custom Port

```bash
# Docker
docker run -p 8082:8080 italic/adminer-desc-sort

# Docker Compose
echo "ADMINER_PORT=8082" > .env
docker-compose up -d
```

## 🛠️ Development

### Build Locally

```bash
docker build -t italic/adminer-desc-sort:local .
docker run -p 8081:8080 italic/adminer-desc-sort:local
```

### Publish to Docker Hub

```bash
docker login
docker build -t italic/adminer-desc-sort:1.0.0 .
docker build -t italic/adminer-desc-sort:latest .
docker push italic/adminer-desc-sort:1.0.0
docker push italic/adminer-desc-sort:latest
```

## 🔧 Troubleshooting

### Port Already in Use
```bash
docker run -p 8082:8080 italic/adminer-desc-sort
```

### Database Connection Issues
```bash
# Check network connectivity
docker run --rm --network your-db-network alpine ping your-db-host
```

### Reset Everything
```bash
docker stop adminer-custom && docker rm adminer-custom
docker run -d --name adminer-custom -p 8081:8080 italic/adminer-desc-sort
```

## 📁 Project Structure

```
├── docker-compose.yml        # Local build
├── docker-compose.hub.yml    # Docker Hub image
├── Dockerfile               # Image build
├── plugin-desc-sort.php     # Universal plugin
├── setup.sh                # Auto-setup script
└── README.md               # This file
```

## 🧪 Testing

1. Start Adminer: `docker run -p 8081:8080 italic/adminer-desc-sort`
2. Open: http://localhost:8081
3. Connect to your database
4. Select a table
5. Verify: Data is sorted DESC on `id` column by default!

## 📄 License

Apache License 2.0 (same as Adminer)

## 🔗 Links

- **GitHub**: https://github.com/germain-italic/adminer-docker-custom
- **Docker Hub**: https://hub.docker.com/r/italic/adminer-desc-sort
- **Issues**: https://github.com/germain-italic/adminer-docker-custom/issues