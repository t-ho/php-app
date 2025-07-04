#!/bin/bash
set -e

# Replace DOMAIN_PLACEHOLDER with actual domain
if [ ! -z "$DOMAIN" ]; then
    echo "🔧 Configuring SSL for domain: $DOMAIN"
    sed -i "s/DOMAIN_PLACEHOLDER/$DOMAIN/g" /etc/apache2/sites-available/000-default.conf
    sed -i "s/DOMAIN_PLACEHOLDER/$DOMAIN/g" /etc/apache2/sites-available/default-ssl.conf
fi

# Check if SSL certificates exist
if [ -f "/etc/letsencrypt/live/$DOMAIN/fullchain.pem" ] && [ -f "/etc/letsencrypt/live/$DOMAIN/privkey.pem" ]; then
    echo "✅ SSL certificates found for $DOMAIN"
    a2ensite default-ssl
else
    echo "⚠️  SSL certificates not found for $DOMAIN"
    echo "🔒 Starting without SSL (HTTP only)"
    a2dissite default-ssl
fi

# Start Apache
echo "🚀 Starting Apache..."
exec "$@"