#!/bin/bash

# Render Start Script for Laravel
# This script runs when the service starts

echo "🚀 Starting Laravel application..."

# Wait for database to be ready (optional, useful for first deployment)
# Uncomment if needed:
# echo "⏳ Waiting for database..."
# until php artisan db:show 2>/dev/null; do
#   echo "Database is unavailable - sleeping"
#   sleep 2
# done

# Run migrations (only if needed, uncomment for first deployment)
# echo "🗄️  Running database migrations..."
# php artisan migrate --force

# Clear and cache config
echo "⚙️  Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the application
echo "🌐 Starting PHP server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT

