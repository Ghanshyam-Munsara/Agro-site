#!/bin/bash

# Render Build Script for Laravel
# This script runs during the build phase on Render

echo "🚀 Starting Laravel build process..."

# Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Cache configuration
echo "⚙️  Caching configuration..."
php artisan config:cache

# Cache routes
echo "🛣️  Caching routes..."
php artisan route:cache

# Cache views
echo "👁️  Caching views..."
php artisan view:cache

# Create storage directories
echo "📁 Creating storage directories..."
mkdir -p storage/app/public/products
mkdir -p storage/app/public/services
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Set permissions
echo "🔐 Setting storage permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Create storage link
echo "🔗 Creating storage symbolic link..."
php artisan storage:link || true

echo "✅ Build process completed successfully!"

