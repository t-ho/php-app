#!/bin/bash

# Development deployment script
echo "🚀 Starting development environment..."

# Copy development env file
cp .env.development .env

# Build and start development containers
sudo docker compose down -v
sudo docker compose up --build -d

echo "✅ Development environment started!"
echo "📱 Application: http://localhost:8080"
echo "🗄️  phpMyAdmin: http://localhost:8081"
echo "   Username: blog_user"
echo "   Password: blog_password"

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 10

# Run fixtures
echo "📊 Wanting sample data? Run command below:"
echo "docker exec -it php-app composer run schema:fixtures"

echo "🎉 Development setup complete!"

