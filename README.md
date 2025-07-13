# Adminer Custom - Default DESC Sort

Adminer with automatic DESC sorting on primary keys by default.

## Features

- ✅ Automatic **DESC** sorting on `id` column by default
- ✅ Based on Adminer 5.3.0 (latest stable version)
- ✅ Simple Docker configuration
- ✅ Automatic redirection to force DESC order

## Quick Installation

```bash
# Clone the repository
git clone https://github.com/germain-italic/adminer-docker-custom.git
cd adminer-docker-custom

# Automatic setup
chmod +x setup.sh
./setup.sh
```

## Manual Installation

```bash
# 1. Copy configuration
cp .env.example .env

# 2. Modify port if needed (optional)
nano .env

# 3. Create Docker network
docker network create adminer-network

# 4. Build and start
docker-compose up -d --build
```

## Access

- **URL**: http://localhost:8081
- **Default port**: 8081 (configurable in `.env`)

## Configuration

### Environment variables (`.env`)

```bash
# Adminer listening port
ADMINER_PORT=8081

# Default database server (optional)
DB_HOST=localhost
```

### Port customization

```bash
# Change port in .env
echo "ADMINER_PORT=8082" > .env

# Restart
docker-compose down
docker-compose up -d
```

## How it works

The plugin intercepts selection requests and:

1. **Detects** if no order is specified in the URL
2. **Redirects** automatically with `order[0]=id&desc[0]=1`
3. **Forces** DESC sorting on the `id` column by default

### Plugin code

```php
// Force DESC by default if no order specified
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

## Useful commands

```bash
# View logs
docker-compose logs -f

# Restart
docker-compose restart

# Stop
docker-compose down

# Rebuild
docker-compose up -d --build

# Clean up
docker-compose down -v
docker rmi adminer-custom_adminer
```

## Troubleshooting

### Port already in use

```bash
# Change port in .env
ADMINER_PORT=8082

# Restart
docker-compose down && docker-compose up -d
```

### Docker network issue

```bash
# Create network if needed
docker network create adminer-network

# Restart
docker-compose up -d
```

### Complete reset

```bash
# Clean everything
docker-compose down -v
docker rmi adminer-custom_adminer
docker system prune -f

# Restart
docker-compose up -d --build
```

## Project structure

```
adminer-custom/
├── docker-compose.yml    # Docker configuration
├── Dockerfile           # Custom image
├── custom-adminer.php   # DESC sort plugin
├── setup.sh            # Installation script
├── .env.example        # Example configuration
├── .env                # Local configuration
└── README.md           # Documentation
```

## Testing

1. **Open**: http://localhost:8081
2. **Connect** to your database
3. **Select** a table
4. **Verify**: Data is sorted in **DESC** order on the `id` column by default!

## Compatibility

- ✅ Adminer 5.3.0
- ✅ PHP 8.x
- ✅ Docker & Docker Compose
- ✅ All databases supported by Adminer

## Repository

- **GitHub**: https://github.com/germain-italic/adminer-docker-custom
- **Issues**: https://github.com/germain-italic/adminer-docker-custom/issues

## License

Same license as Adminer (Apache License 2.0)