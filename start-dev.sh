#!/bin/bash

echo "🚀 Starting PHP Blog Development Environment"
echo "============================================="

# Check if Docker is available
if ! command -v docker &> /dev/null; then
    echo "❌ Docker not found. Please install Docker first."
    exit 1
fi

# Check if we can run Docker commands
if ! docker info &> /dev/null; then
    echo "⚠️  Docker permission issue detected."
    echo "You may need to run: sudo usermod -aG docker $USER"
    echo "Then logout and login again."
    echo ""
    USE_SUDO="sudo "
else
    USE_SUDO=""
fi

echo "📦 Starting all services (PHP, Vite, Database, phpMyAdmin)..."
${USE_SUDO}docker compose up -d

if [ $? -eq 0 ]; then
    echo "✅ All services started successfully!"
    echo ""
    echo "⏳ Waiting for services to be ready..."
    sleep 5
    echo "🎉 Development environment ready!"
else
    echo "❌ Failed to start services. Check Docker logs:"
    echo "   ${USE_SUDO}docker compose logs"
fi

echo ""
echo "🌍 Your applications:"
echo "   📱 Main app: http://localhost:8080"
echo "   🔥 Vite dev: http://localhost:3000"
echo "   🗄️  phpMyAdmin: http://localhost:8081"