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

# Create directories
mkdir -p certbot/conf certbot/www

echo "🚀 Starting services (HTTP only first)..."
docker-compose -f docker-compose.ssl-apache.yml down
docker-compose -f docker-compose.ssl-apache.yml up -d php-app mariadb

echo "⏳ Waiting for services to start..."
sleep 10

echo "🔒 Obtaining SSL certificate..."
docker-compose -f docker-compose.ssl-apache.yml run --rm certbot

if [ $? -eq 0 ]; then
    echo "✅ SSL certificate obtained successfully!"
    echo "🔄 Restarting Apache with SSL..."
    docker-compose -f docker-compose.ssl-apache.yml restart php-app
    
    echo "🎉 SSL deployment complete!"
    echo "🌐 Your app is available at: https://$DOMAIN"
else
    echo "❌ SSL certificate failed. Check DNS settings."
    echo "🌐 App is running on HTTP: http://$DOMAIN"
fi

echo ""
echo "📋 Next steps:"
echo "1. Ensure DNS points to this server"
echo "2. Set up certificate renewal: add to crontab"
echo "   0 12 * * * docker-compose -f docker-compose.ssl-apache.yml run --rm certbot renew && docker-compose -f docker-compose.ssl-apache.yml restart php-app"