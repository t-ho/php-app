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

# Build and start production containers (no phpMyAdmin)
docker compose -f docker-compose.prod.yml down
docker compose -f docker-compose.prod.yml up --build -d

echo "✅ Production environment started!"
echo "📱 Application: http://localhost (or your domain)"

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 10

# Run schema (no fixtures in production)
echo "🗄️  Setting up database schema..."
docker exec -it php-app composer run schema:load

echo "🎉 Production deployment complete!"
echo "⚠️  Remember to:"
echo "   - Update .env.production with strong passwords"
echo "   - Configure SSL certificates"
echo "   - Set up domain DNS"
echo "   - Configure firewall"

