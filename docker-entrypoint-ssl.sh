#!/bin/bash
set -e

# Ensure certbot directory exists and is writable
if [ -w /var/www/certbot ]; then
    mkdir -p /var/www/certbot/.well-known/acme-challenge
    chown -R www-data:www-data /var/www/certbot
    echo "✅ Certbot challenge directory ready"
else
    echo "⚠️  Certbot directory not writable, will be created by deployment script"
fi

# Replace DOMAIN_PLACEHOLDER with actual domain
if [ ! -z "$DOMAIN" ]; then
    echo "🔧 Configuring for domain: $DOMAIN"
fi

# Check if SSL certificates exist and configure accordingly
if [ -f "/etc/letsencrypt/live/$DOMAIN/fullchain.pem" ] && [ -f "/etc/letsencrypt/live/$DOMAIN/privkey.pem" ]; then
    echo "✅ SSL certificates found for $DOMAIN"
    echo "🔒 Configuring HTTPS with SSL redirect"
    
    # Copy HTTPS config (with SSL and redirect)
    cp /var/www/html/apache-https-config.conf /etc/apache2/sites-available/000-default.conf
    sed -i "s/DOMAIN_PLACEHOLDER/$DOMAIN/g" /etc/apache2/sites-available/000-default.conf
    
    # Disable default SSL site (conflicts with our config)
    a2dissite default-ssl 2>/dev/null || true
else
    echo "⚠️  SSL certificates not found for $DOMAIN"
    echo "🔓 Configuring HTTP only (no SSL redirect)"
    
    # Copy HTTP-only config (no SSL references)
    echo "📝 Using HTTP-only configuration"
    cp /var/www/html/apache-http-config.conf /etc/apache2/sites-available/000-default.conf
    sed -i "s/DOMAIN_PLACEHOLDER/$DOMAIN/g" /etc/apache2/sites-available/000-default.conf
    
    # Disable SSL site completely
    a2dissite default-ssl 2>/dev/null || true
    
    # Make sure no SSL modules cause issues
    echo "🔧 HTTP configuration applied"
fi

echo "📋 Final Apache configuration:"
head -10 /etc/apache2/sites-available/000-default.conf

# Start Apache
echo "🚀 Starting Apache..."
exec "$@"