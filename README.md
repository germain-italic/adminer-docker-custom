# Adminer Custom - Default DESC Sort

Adminer with automatic DESC sorting on primary keys by default.

## Features

- ✅ Automatic **DESC** sorting on `id` column by default
- ✅ Based on Adminer 4.8.1 (stable version)
- ✅ Simple Docker configuration
- ✅ Automatic redirection to force DESC order
- ✅ Available on Docker Hub for instant use

## Quick Installation

### Option 1: Using Docker Hub (Recommended)

```bash
# Create network
docker network create adminer-network

# Run directly from Docker Hub
docker run -d \
  --name adminer-custom \
  --network adminer-network \
  -p 8081:8080 \
  italic/adminer-desc-sort:latest
```

### Option 2: Using Docker Compose with Docker Hub

```bash
# Download docker-compose.hub.yml
curl -O https://raw.githubusercontent.com/germain-italic/adminer-docker-custom/master/docker-compose.hub.yml

# Create network and start
docker network create adminer-network
docker-compose -f docker-compose.hub.yml up -d
```

### Option 3: Build from source

```bash
# Clone the repository
git clone https://github.com/germain-italic/adminer-docker-custom.git
cd adminer-docker-custom

# Automatic setup
chmod +x setup.sh
./setup.sh
```

## Manual Installation (Build from source)

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

## Docker Hub

- **Image**: `italic/adminer-desc-sort`
- **Tags**: `latest`, `1.0.0`
- **Docker Hub**: https://hub.docker.com/r/italic/adminer-desc-sort

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

## Usage Examples

### Quick test with Docker

```bash
# Start Adminer with DESC sort
docker run -p 8081:8080 italic/adminer-desc-sort

# Access: http://localhost:8081
```

### With existing database network

```bash
# Connect to existing database network
docker run -d \
  --name adminer-custom \
  --network your-db-network \
  -p 8081:8080 \
  italic/adminer-desc-sort
```

### With environment variables

```bash
# Set default database server
docker run -d \
  --name adminer-custom \
  -p 8081:8080 \
  -e ADMINER_DEFAULT_SERVER=your-db-host \
  italic/adminer-desc-sort
```

## Useful commands

```bash
# View logs
docker logs adminer-custom

# Restart
docker restart adminer-custom

# Stop and remove
docker stop adminer-custom
docker rm adminer-custom

# Update to latest version
docker pull italic/adminer-desc-sort:latest
docker stop adminer-custom
docker rm adminer-custom
docker run -d --name adminer-custom -p 8081:8080 italic/adminer-desc-sort:latest
```

## Development

### Build locally

```bash
# Clone repository
git clone https://github.com/germain-italic/adminer-docker-custom.git
cd adminer-docker-custom

# Build image
docker build -t italic/adminer-desc-sort:local .

# Test locally
docker run -p 8081:8080 italic/adminer-desc-sort:local
```

### Publish to Docker Hub

```bash
# Login to Docker Hub
docker login

# Build and tag
docker build -t italic/adminer-desc-sort:1.0.0 .
docker build -t italic/adminer-desc-sort:latest .

# Push to Docker Hub
docker push italic/adminer-desc-sort:1.0.0
docker push italic/adminer-desc-sort:latest
```

## Troubleshooting

### Port already in use

```bash
# Use different port
docker run -p 8082:8080 italic/adminer-desc-sort
```

### Docker network issue

```bash
# Create network if needed
docker network create adminer-network

# Connect to network
docker run -d --name adminer-custom --network adminer-network -p 8081:8080 italic/adminer-desc-sort
```

### Database connection issues

```bash
# Check if database is accessible
docker run --rm --network your-db-network alpine ping your-db-host

# Use host networking (Linux only)
docker run --network host italic/adminer-desc-sort
```

### Complete reset

```bash
# Remove everything
docker stop adminer-custom
docker rm adminer-custom
docker rmi italic/adminer-desc-sort

# Start fresh
docker run -d --name adminer-custom -p 8081:8080 italic/adminer-desc-sort:latest
```

## Project structure

```
adminer-docker-custom/
├── docker-compose.yml        # Local build configuration
├── docker-compose.hub.yml    # Docker Hub configuration
├── Dockerfile               # Custom image build
├── custom-adminer.php       # DESC sort plugin
├── setup.sh                # Installation script
├── .env.example            # Example configuration
├── .env                    # Local configuration
└── README.md               # Documentation
```

## Testing

1. **Start Adminer**: `docker run -p 8081:8080 italic/adminer-desc-sort`
2. **Open**: http://localhost:8081
3. **Connect** to your database
4. **Select** a table
5. **Verify**: Data is sorted in **DESC** order on the `id` column by default!

## Compatibility

- ✅ Adminer 4.8.1
- ✅ PHP 8.x
- ✅ Docker & Docker Compose
- ✅ All databases supported by Adminer
- ✅ Linux, macOS, Windows (Docker Desktop)

## Versions

- **v1.0.0**: Initial release with DESC sort functionality
- **latest**: Always points to the most recent stable version

## Repository

- **GitHub**: https://github.com/germain-italic/adminer-docker-custom
- **Docker Hub**: https://hub.docker.com/r/italic/adminer-desc-sort
- **Issues**: https://github.com/germain-italic/adminer-docker-custom/issues

## License

Same license as Adminer (Apache License 2.0)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test with Docker
5. Submit a pull request

## Support

- 📖 **Documentation**: This README
- 🐛 **Issues**: GitHub Issues
- 💬 **Discussions**: GitHub Discussions
- 🐳 **Docker Hub**: https://hub.docker.com/r/italic/adminer-desc-sort