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
- ✅ Compatible with Adminer 5.x plugin architecture
- ✅ Based on Adminer 5.x (always latest stable version)  
- ✅ No Adminer modifications required
- ✅ Works with any primary key name (id, user_id, estimate_file_id, etc.)
- ✅ Respects user-defined sorting when columns are clicked
- ✅ PHP 7.0+ compatible
- ✅ **Docker optimized** plugin loading system

## 🔧 How it works

### Plugin Logic

Adminer automatically loads plugins from the `adminer-plugins/` directory. The plugin hooks into Adminer's `selectQueryBuild` method to:

1. **Check for existing order**: If user clicked a column header, respect their choice
2. **Detect primary key**: Query `SHOW COLUMNS` to find the primary key
3. **Fallback strategy**: If no primary key, look for columns containing "id"
4. **Build query**: Construct complete SELECT with `ORDER BY column DESC`
5. **Error handling**: Return empty string to use default query if anything fails

### Docker Integration

The Docker image uses the official Adminer Docker Hub plugin loading system:
- Plugin source: `/var/www/html/adminer-plugins/desc-sort-plugin.php`
- Plugin wrapper: `/var/www/html/plugins-enabled/desc-sort.php` (returns plugin instance)
- Automatic loading: No configuration required

### Technical Details

The plugin uses proper Adminer namespace functions:
- `\Adminer\connection()` for database access
- `\Adminer\idf_escape()` for safe identifier escaping

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

For **non-Docker** environments, Adminer automatically loads `.php` files from the `adminer-plugins/` directory. Each plugin must:

```php
class PluginName {
    function methodName($params) {
        // Plugin logic here
        return $result; // Or "" for default behavior
    }
}
```

For **Docker** environments, plugins need a wrapper in `plugins-enabled/`:

```php
<?php
require_once('../adminer-plugins/plugin-name.php');
return new PluginName();
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

### Plugin not loading in Docker
- **Check loaded plugins**: Look for the plugin name in Adminer's "Loaded plugins" section
- **Verify build**: Ensure `docker build` completed without errors
- **Check logs**: Run `docker-compose logs -f` to see any PHP errors
- **Verify wrapper**: Ensure `/var/www/html/plugins-enabled/desc-sort.php` exists in container

### Plugin not loading (manual installation)
- **Check plugins directory**: Verify `adminer-plugins/` exists next to `index.php`
- **Verify file permissions**: Ensure plugin files are readable by web server
- **Check PHP syntax**: Verify plugin file has no syntax errors
- **Review logs**: Check PHP error logs for detailed error messages

### No DESC sorting
- **Verify table structure**: Check table has a primary key or "id" column
- **Check browser network**: Look for custom ORDER BY in SQL queries
- **Fresh table view**: Ensure you're viewing fresh table (not after clicking columns)
- **Database support**: Verify your database supports SHOW COLUMNS syntax

### Connection errors
- **Plugin namespace**: Ensure `\Adminer\connection()` is used with proper namespace
- **Database connectivity**: Test database connection outside the plugin
- **Function availability**: Verify Adminer functions are accessible in plugin context

### PHP Fatal Errors
- **PHP compatibility**: Ensure PHP 7.0+ compatibility
- **Namespace usage**: Check that all Adminer functions use full namespace
- **Error handling**: Verify plugin returns empty string on error, not null
- **Function existence**: Ensure all used functions exist in current Adminer version

### Docker specific issues
- **Container rebuild**: Try rebuilding the Docker image completely
- **Plugin wrapper**: Verify the plugin wrapper in `plugins-enabled/` is correct
- **File permissions**: Check that www-data user can read plugin files

## 📄 License

Apache License 2.0 (same as Adminer)

## 🔗 Links

- **GitHub**: https://github.com/germain-italic/adminer-docker-custom
- **Docker Hub**: https://hub.docker.com/r/italic/adminer-desc-sort
- **Issues**: https://github.com/germain-italic/adminer-docker-custom/issues
- **Adminer**: https://www.adminer.org/
- **Plugin Documentation**: https://www.adminer.org/plugins/

## 📋 Changelog

### Version 2.1.0
- ✅ **Fixed Docker plugin loading**: Proper integration with official Adminer Docker image
- ✅ **Dual loading system**: Works with both `adminer-plugins/` (manual) and `plugins-enabled/` (Docker)
- ✅ **Improved documentation**: Added Docker-specific troubleshooting and development guide
- ✅ **Enhanced error handling**: Better PHP error handling and wrapper structure

### Version 2.0.0
- ✅ **PHP 7.0+ compatibility**: Full compatibility with modern PHP versions
- ✅ **Adminer 5.x support**: Updated for latest Adminer API
- ✅ **Improved plugin architecture**: Cleaner code structure and better error handling
- ✅ **Docker integration**: Full Docker support with automatic plugin loading