# Docker Deployment Guide for AgroSite Backend

This guide covers deploying the AgroSite Laravel backend using Docker on Render or other platforms.

## 📋 Prerequisites

- Docker installed locally (for testing)
- Docker account (if using Docker Hub)
- Render account (for deployment)
- PostgreSQL database (Render PostgreSQL or external)

## 🐳 Local Development with Docker

### Quick Start

1. **Copy environment file:**
   ```bash
   cp .env.example .env
   ```

2. **Update `.env` file with Docker database settings:**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=postgres
   DB_PORT=5432
   DB_DATABASE=agrosite_db
   DB_USERNAME=agrosite_user
   DB_PASSWORD=agrosite_password
   ```

3. **Start services:**
   ```bash
   docker-compose up -d
   ```

4. **Install dependencies and setup:**
   ```bash
   docker-compose exec app composer install
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate
   docker-compose exec app php artisan storage:link
   ```

5. **Access the application:**
   - API: http://localhost:8000
   - Database: localhost:5432

### Useful Commands

```bash
# View logs
docker-compose logs -f app

# Run artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

# Access container shell
docker-compose exec app sh

# Stop services
docker-compose down

# Stop and remove volumes (clean slate)
docker-compose down -v
```

## 🚀 Deployment to Render

### Option 1: Using Docker (Recommended for consistency)

1. **Use the Docker-based render.yaml:**
   ```bash
   # Rename or use render.docker.yaml
   cp render.docker.yaml render.yaml
   ```

2. **Deploy via Render Dashboard:**
   - Go to https://dashboard.render.com
   - Click "New +" → "Blueprint"
   - Connect your Git repository
   - Render will read `render.yaml` and deploy using Docker

3. **Or deploy manually:**
   - Create a new "Web Service"
   - Set Environment to "Docker"
   - Set Dockerfile Path to: `./Dockerfile.production`
   - Set Docker Context to: `.`
   - Configure environment variables

### Option 2: Using Native PHP (Current setup)

Your current `render.yaml` uses native PHP deployment (no Docker). This is simpler and works well for Laravel.

**To use this:**
- Keep your current `render.yaml`
- Render will automatically detect PHP and use the build scripts

## 📦 Docker Files Overview

### `Dockerfile`
- Development/standard Docker image
- Uses PHP 8.0 with FPM
- Includes all necessary extensions
- Optimized for local development

### `Dockerfile.production`
- Multi-stage build for production
- Smaller image size
- Production-optimized
- No development dependencies

### `docker-compose.yml`
- Local development setup
- Includes PostgreSQL database
- Easy to start/stop services

### `docker-compose.prod.yml`
- Production Docker Compose
- Uses external database
- Environment variables from `.env.production`

### `.dockerignore`
- Excludes unnecessary files from Docker build
- Reduces image size
- Speeds up builds

## 🔧 Configuration

### Environment Variables

Required environment variables for Docker deployment:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key
APP_URL=https://your-app.onrender.com

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=agrosite_db
DB_USERNAME=your-username
DB_PASSWORD=your-password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Port Configuration

- **Development:** Port 8000 (configurable in docker-compose.yml)
- **Render:** Uses `$PORT` environment variable (automatically set by Render)

## 🛠️ Building and Testing Locally

### Build Docker Image

```bash
# Development image
docker build -t agrosite-api:dev .

# Production image
docker build -f Dockerfile.production -t agrosite-api:prod .
```

### Run Container

```bash
# Development
docker run -p 8000:8000 --env-file .env agrosite-api:dev

# Production
docker run -p 8000:8000 --env-file .env.production agrosite-api:prod
```

## 📝 Database Migrations

### Local Development

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Run with seeders
docker-compose exec app php artisan migrate --seed

# Rollback
docker-compose exec app php artisan migrate:rollback
```

### Production (Render)

1. **Via Render Shell:**
   - Go to your service dashboard
   - Click "Shell" tab
   - Run: `php artisan migrate --force`

2. **Via Deploy Script:**
   - Add to `render-start.sh`:
   ```bash
   php artisan migrate --force
   ```

## 🔍 Troubleshooting

### Container won't start

```bash
# Check logs
docker-compose logs app

# Check container status
docker-compose ps

# Rebuild containers
docker-compose up --build
```

### Database connection issues

- Verify `DB_HOST` matches service name in docker-compose.yml
- Check database container is running: `docker-compose ps postgres`
- Test connection: `docker-compose exec app php artisan db:test`

### Permission issues

```bash
# Fix storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Port already in use

```bash
# Change port in docker-compose.yml
ports:
  - "8001:8000"  # Use port 8001 instead
```

## 🎯 Best Practices

1. **Use multi-stage builds** for production (already in Dockerfile.production)
2. **Never commit `.env` files** - use `.env.example`
3. **Use health checks** (already configured in Dockerfile)
4. **Optimize images** - use Alpine Linux base images
5. **Cache dependencies** - Docker layer caching for faster builds
6. **Use secrets management** - Render environment variables for sensitive data

## 📚 Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Render Docker Guide](https://render.com/docs/docker)
- [Laravel Deployment](https://laravel.com/docs/deployment)

## 🆘 Support

If you encounter issues:
1. Check application logs: `docker-compose logs app`
2. Check database logs: `docker-compose logs postgres`
3. Verify environment variables
4. Check Render deployment logs

