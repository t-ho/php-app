#!/bin/bash

# DigitalOcean deployment script
echo "🌊 Deploying to DigitalOcean..."

# Variables (update these)
DROPLET_IP="YOUR_DROPLET_IP"
DROPLET_USER="root"
APP_DIR="/var/www/php-app"

echo "📋 Before running this script:"
echo "1. Create a DigitalOcean droplet with Docker"
echo "2. Update DROPLET_IP in this script"
echo "3. Ensure SSH key access to your droplet"
echo "4. Update .env.production with your settings"
echo ""

read -p "Have you completed the above steps? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Please complete setup steps first"
    exit 1
fi

echo "📦 Creating deployment package..."
tar -czf deploy.tar.gz \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='.env' \
    --exclude='.env.development' \
    --exclude='deploy.tar.gz' \
    .

echo "📤 Uploading to DigitalOcean..."
scp deploy.tar.gz $DROPLET_USER@$DROPLET_IP:/tmp/

echo "🚀 Deploying on server..."
ssh $DROPLET_USER@$DROPLET_IP << 'EOF'
    # Create app directory
    mkdir -p /var/www/php-app
    cd /var/www/php-app
    
    # Extract files
    tar -xzf /tmp/deploy.tar.gz
    
    # Copy production env
    cp .env.production .env
    
    # Install Docker and Docker Compose if not installed
    if ! command -v docker &> /dev/null; then
        curl -fsSL https://get.docker.com -o get-docker.sh
        sh get-docker.sh
        systemctl enable docker
        systemctl start docker
    fi
    
    if ! command -v docker-compose &> /dev/null; then
        curl -L "https://github.com/docker/compose/releases/download/v2.20.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
        chmod +x /usr/local/bin/docker-compose
    fi
    
    # Deploy
    docker-compose -f docker-compose.prod.yml down
    docker-compose -f docker-compose.prod.yml up --build -d
    
    # Wait and setup database
    sleep 15
    docker exec php-app composer run schema:load
    
    # Cleanup
    rm /tmp/deploy.tar.gz
    
    echo "✅ Deployment complete!"
EOF

# Cleanup local file
rm deploy.tar.gz

echo "🎉 Deployment to DigitalOcean complete!"
echo "📱 Your app should be available at: http://$DROPLET_IP"
echo ""
echo "🔒 Next steps:"
echo "1. Point your domain to $DROPLET_IP"
echo "2. Set up SSL with Let's Encrypt"
echo "3. Configure firewall (ufw)"
echo "4. Set up automated backups"