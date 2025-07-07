#!/bin/bash

# Production deployment script
echo "🚀 Starting production deployment..."

# Check if production env file exists
if [ ! -f ".env.production" ]; then
  echo "❌ .env.production file not found!"
  echo "Please create .env.production with your production settings"
  exit 1
fi

# Copy production env file
cp .env.production .env

# Build production assets using Docker
echo "🔨 Building JavaScript assets for production..."
if [ ! -f "package.json" ]; then
    echo "❌ package.json not found! JavaScript build system not set up."
    exit 1
fi

# Use Docker to build assets (no need for npm on host)
echo "📦 Installing Node.js dependencies via Docker..."
docker run --rm -v "$(pwd):/app" -w /app node:20-alpine npm install

echo "🔨 Building production assets via Docker..."
docker run --rm -v "$(pwd):/app" -w /app node:20-alpine npm run build

if [ ! -d "public/dist" ]; then
    echo "❌ Build failed! No dist directory created."
    exit 1
fi

echo "✅ JavaScript assets built successfully!"

# Build and start production containers (no phpMyAdmin)
docker compose -f docker-compose.prod.yml down
docker compose -f docker-compose.prod.yml up --build -d

echo "✅ Production environment started!"
echo "📱 Application: http://localhost (or your domain)"

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 10

# Run migrations (no sample data in production)
echo "🗄️  Setting up database schema..."
docker exec php-app vendor/bin/phinx migrate

echo "🎉 Production deployment complete!"
echo ""
echo "📊 Deployment Summary:"
echo "   ✅ JavaScript assets built and optimized"
echo "   ✅ Production containers running"
echo "   ✅ Database schema loaded"
echo ""
echo "⚠️  Remember to:"
echo "   - Update .env.production with strong passwords"
echo "   - Configure SSL certificates (use deploy-ssl-apache.sh)"
echo "   - Set up domain DNS"
echo "   - Configure firewall"
