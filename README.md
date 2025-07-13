# Adminer Custom - Default DESC Sort

Adminer with automatic DESC sorting on primary keys by default.

## 🚀 Quick Installation

### Option 1: Docker Hub (Recommended)

```bash
# Direct launch
docker run -p 8081:8080 italic/adminer-desc-sort

# Or with docker-compose
curl -O https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/docker-compose.hub.yml
docker-compose -f docker-compose.hub.yml up -d
```

### Option 2: Manual Installation

```bash
# 1. Download the plugin
wget https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/adminer-plugins/desc-sort-plugin.php

# 2. Create plugins directory
mkdir -p adminer-plugins

# 3. Move the plugin
mv desc-sort-plugin.php adminer-plugins/

# 4. Create configuration file
echo '<?php include_once "adminer-plugins/desc-sort-plugin.php"; return array(new AdminerDescSort);' > adminer-plugins.php
```

### Option 3: Build from source

```bash
git clone https://github.com/germain-italic/adminer-docker-custom.git
cd adminer-docker-custom
chmod +x setup.sh
./setup.sh
```

## ✨ Features

- ✅ Automatic **DESC** sorting on **primary key** column by default
- ✅ Compatible with standard Adminer plugin architecture
- ✅ Based on Adminer 5.x (always latest stable version)
- ✅ No Adminer modifications required

## 🔧 How it works

The plugin uses Adminer's standard architecture:
- Placed in `adminer-plugins/`
- Loaded via `adminer-plugins.php`
- Automatically detects primary key and adds `ORDER BY primary_key DESC`
- Works with any primary key name (id, user_id, estimate_file_id, etc.)

## 🌐 Access

- **Default URL**: http://localhost:8081
- **Port**: Configurable via `ADMINER_PORT` variable

## ⚙️ Configuration

### Environment variables

```bash
# Port (default: 8081)
ADMINER_PORT=8081

# Default database server (optional)
DB_HOST=localhost
```

### Custom port

```bash
# Docker
docker run -p 8082:8080 italic/adminer-desc-sort

# Docker Compose
echo "ADMINER_PORT=8082" > .env
docker-compose up -d
```

## 🛠️ Development

### Local build

```bash
docker build -t italic/adminer-desc-sort:local .
docker run -p 8081:8080 italic/adminer-desc-sort:local
```

## 🧪 Testing

1. Start Adminer: `docker run -p 8081:8080 italic/adminer-desc-sort`
2. Open: http://localhost:8081
3. Connect to your database
4. Select a table
5. Verify: Data is sorted DESC on `id` column by default!

## 📁 Project structure

```
├── docker-compose.yml        # Local build
├── docker-compose.hub.yml    # Docker Hub image
├── Dockerfile               # Image construction
├── plugin-desc-sort.php     # Adminer plugin
├── setup.sh                # Automatic installation script
└── README.md               # This file
```

## 📄 License

Apache License 2.0 (same as Adminer)

## 🔗 Links

- **GitHub**: https://github.com/germain-italic/adminer-docker-custom
- **Docker Hub**: https://hub.docker.com/r/italic/adminer-desc-sort
- **Issues**: https://github.com/germain-italic/adminer-docker-custom/issues