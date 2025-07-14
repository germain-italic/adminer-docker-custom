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
# 1. Download Adminer
wget https://github.com/vrana/adminer/releases/download/v5.3.0/adminer-5.3.0.php
mv adminer-5.3.0.php index.php

# 2. Download the plugin
mkdir adminer-plugins
wget -O adminer-plugins/desc-sort-plugin.php https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/adminer-plugins/desc-sort-plugin.php
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
- ✅ Works with any primary key name (id, user_id, estimate_file_id, etc.)
- ✅ Respects user-defined sorting when columns are clicked
- ✅ PHP 7.0+ compatible

## 🔧 How it works

The plugin uses Adminer's standard architecture:
- Placed in `adminer-plugins/`
- Loaded via `adminer-plugins.php` 
- Automatically detects primary key and adds `ORDER BY primary_key DESC`
- Only applies when no user-defined order exists
- Falls back to any column containing "id" if no primary key found

### Technical Details

The plugin hooks into Adminer's `selectQueryBuild` method to:

1. **Check for existing order**: If user clicked a column header, respect their choice
2. **Detect primary key**: Query `SHOW COLUMNS` to find the primary key
3. **Fallback strategy**: If no primary key, look for columns containing "id"
4. **Build query**: Construct complete SELECT with `ORDER BY column DESC`
5. **Error handling**: Return empty string to use default query if anything fails

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

### Plugin Development

The plugin follows Adminer's plugin architecture:

```php
class AdminerDescSort {
    function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
        // Plugin logic here
        return $custom_query; // Or "" for default
    }
}
```

Key considerations:
- Use `\Adminer\connection()` for database access
- Use `\Adminer\idf_escape()` for identifier escaping  
- Return empty string to fall back to default behavior
- Handle all exceptions gracefully

## 🧪 Testing

1. Start Adminer: `docker run -p 8081:8080 italic/adminer-desc-sort`
2. Open: http://localhost:8081
3. Connect to your database
4. Select a table
5. **Verify**: Data is sorted DESC on primary key by default!
6. **Test user interaction**: Click column headers to verify custom sorting works

### Expected Behavior

- **Fresh table view**: Automatically sorted by primary key DESC
- **After clicking column**: Respects user's sort choice
- **No primary key**: Falls back to first "id" column found
- **No id columns**: Uses Adminer's default behavior

## 📁 Project structure

```
├── docker-compose.yml              # Local build
├── docker-compose.hub.yml          # Docker Hub image  
├── Dockerfile                      # Image construction
├── adminer-plugins/
│   └── desc-sort-plugin.php       # Main plugin file
├── setup.sh                       # Automatic installation script
└── README.md                      # This documentation
```

## 🐛 Troubleshooting

### Plugin not loading
- Check `adminer-plugins.php` exists and is readable
- Verify plugin file path in configuration
- Check PHP error logs for syntax errors

### No DESC sorting
- Verify table has a primary key or "id" column
- Check browser network tab for custom ORDER BY in queries
- Ensure you're viewing fresh table (not after clicking columns)

### Connection errors
- Plugin uses `\Adminer\connection()` - ensure Adminer is properly loaded
- Check database connectivity outside the plugin

## 📄 License

Apache License 2.0 (same as Adminer)

## 🔗 Links

- **GitHub**: https://github.com/germain-italic/adminer-docker-custom
- **Docker Hub**: https://hub.docker.com/r/italic/adminer-desc-sort
- **Issues**: https://github.com/germain-italic/adminer-docker-custom/issues
- **Adminer**: https://www.adminer.org/
- **Plugin Documentation**: https://www.adminer.org/plugins/