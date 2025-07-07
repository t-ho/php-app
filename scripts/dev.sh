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
echo "   Email: admin@tdev.app"
echo "   Password: Test1234"

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 10

# Install Node.js dependencies if not exist
echo "📦 Checking Node.js dependencies..."
if [ ! -d "node_modules" ]; then
  echo "   Installing Node.js dependencies..."
  docker exec -it php-app npm install
fi

# Check Vite dev server status
echo ""
echo "🔥 JavaScript Hot Reload Status:"
if docker compose ps | grep -q "php-app-vite.*Up"; then
    echo "   ✅ Vite dev server running on http://localhost:3000"
    echo "   ✅ Hot reload active - edit files in assets/js/ to see changes!"
else
    echo "   ⚠️  Vite dev server not running"
    echo "   Run: docker compose restart vite"
fi
echo ""

echo "🎉 Development setup complete!"
echo ""
echo "🌍 Your development environment:"
echo "   📱 Main app: http://localhost:8080"
echo "   🔥 Vite dev: http://localhost:3000"
echo "   🗄️  phpMyAdmin: http://localhost:8081"
