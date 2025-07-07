#!/bin/bash

# SSL deployment with Apache + Certbot
echo "🔐 Deploying with SSL (Apache + Let's Encrypt)..."

# Check if .env.ssl exists and has been configured
if [ ! -f ".env.ssl" ]; then
  echo "❌ .env.ssl file not found!"
  echo "Please edit .env.ssl with your domain and email settings"
  exit 1
fi

# Source env file to check variables
source .env.ssl

if [ "$DOMAIN" = "your-domain.com" ] || [ "$SSL_EMAIL" = "your-email@domain.com" ]; then
  echo "❌ Please edit .env.ssl and change DOMAIN and SSL_EMAIL to your actual values"
  exit 1
fi

if [ -z "$DOMAIN" ] || [ -z "$SSL_EMAIL" ]; then
  echo "❌ DOMAIN and SSL_EMAIL must be set in .env.ssl"
  exit 1
fi

echo "🌐 Domain: $DOMAIN"
echo "📧 Email: $SSL_EMAIL"

# Copy SSL env file
cp .env.ssl .env

# Create directories with proper structure
mkdir -p certbot/conf certbot/www
mkdir -p certbot/www/.well-known/acme-challenge
chown -R $USER:$USER certbot/
chmod -R 755 certbot/

echo "🚀 Starting services (HTTP only first)..."
docker compose -f docker-compose.ssl-apache.yml down

echo "🔨 Building fresh Docker image with new configuration..."
docker compose -f docker-compose.ssl-apache.yml build --no-cache php-app

echo "🚀 Starting services..."
docker compose -f docker-compose.ssl-apache.yml up -d php-app mariadb

echo "⏳ Waiting for services to start..."
sleep 15

echo "🔍 Checking container status..."
docker ps -a | grep php-app

echo "📋 Checking container logs..."
docker logs php-app | tail -10

echo "🧪 Testing HTTP access..."
if ! curl -f "http://$DOMAIN" >/dev/null 2>&1; then
  echo "❌ Cannot access http://$DOMAIN"
  echo ""
  echo "🔍 Diagnostics:"
  echo "1. DNS check:"
  nslookup $DOMAIN
  echo ""
  echo "2. Container status:"
  docker ps -f name=php-app
  echo ""
  echo "3. Container logs (last 20 lines):"
  docker logs php-app --tail 20
  echo ""
  echo "4. Port check:"
  ss -tulpn | grep :80 || echo "Port 80 not listening"
  echo ""
  echo "❌ Cannot proceed with SSL until HTTP works"
  echo "💡 Try: docker logs php-app (for full logs)"
  exit 1
fi

echo "✅ HTTP access working"

echo "🔒 Obtaining SSL certificate..."
docker compose -f docker-compose.ssl-apache.yml run --rm certbot

if [ $? -eq 0 ]; then
  echo "✅ SSL certificate obtained successfully!"
  echo "🔄 Restarting Apache with SSL..."
  docker compose -f docker-compose.ssl-apache.yml restart php-app

  echo "🎉 SSL deployment complete!"
  echo ""
  echo "📊 Deployment Summary:"
  echo "   ✅ Docker image built with optimized JavaScript assets"
  echo "   ✅ SSL certificate obtained and configured"
  echo "   ✅ HTTPS enabled with security headers"
  echo "   ✅ Your app is available at: https://$DOMAIN"
else
  echo "❌ SSL certificate failed. Check DNS settings."
  echo "🌐 App is running on HTTP: http://$DOMAIN"
fi

echo ""
echo "📋 Next steps:"
echo "1. Ensure DNS points to this server"
echo "2. Set up certificate renewal: add to crontab"
echo "   0 12 * * * docker compose -f docker-compose.ssl-apache.yml run --rm certbot renew && docker-compose -f docker-compose.ssl-apache.yml restart php-app"
