# Environment Configuration Guide

This PHP App supports multiple deployment environments. Choose the appropriate setup method below.

## 🎯 Quick Setup

### Development (Local)
```bash
cp .env.example .env
./scripts/dev.sh
```

### Production (HTTP)
```bash
cp .env.production .env
nano .env  # Change passwords
./scripts/prod.sh
```

### Production (SSL) - **Recommended**
```bash
nano .env.ssl  # Change DOMAIN, SSL_EMAIL, passwords
./scripts/deploy-ssl-apache.sh
```

## 📁 Environment Files Overview

| File | Purpose | Usage |
|------|---------|-------|
| `.env.example` | Template with all examples | Copy to create other .env files |
| `.env` | Active environment | Auto-created by scripts |
| `.env.development` | Development settings | Used by `./scripts/dev.sh` |
| `.env.production` | Production HTTP | Used by `./scripts/prod.sh` |
| `.env.ssl` | Production SSL | Used by `./scripts/deploy-ssl-apache.sh` |

## 🔧 Configuration Examples

### For New Projects
```bash
# Clone and setup for development
git clone <your-repo>
cd your-project
cp .env.example .env
./scripts/dev.sh
```

### For Production Deployment
```bash
# Upload to server and configure
scp -r . user@server:/var/www/app/
ssh user@server
cd /var/www/app

# Edit SSL config
nano .env.ssl
# Change: DOMAIN, SSL_EMAIL, DB_PASS, MYSQL_ROOT_PASSWORD

# Deploy with SSL
./scripts/deploy-ssl-apache.sh
```

## ⚙️ Configuration Variables

### Required for All Environments
- `DB_HOST` - Database host (usually 'mariadb')
- `DB_NAME` - Database name
- `DB_USER` - Database username
- `DB_PASS` - Database password
- `MYSQL_ROOT_PASSWORD` - MariaDB root password
- `APP_NAME` - Application name
- `APP_ENV` - Environment (development/production)
- `APP_DEBUG` - Debug mode (true/false)

### SSL-Specific (Required for HTTPS)
- `DOMAIN` - Your domain name (e.g., example.com)
- `SSL_EMAIL` - Email for Let's Encrypt notifications

### Port Configuration
- `APP_PORT` - External port for web app
- `PHPMYADMIN_PORT` - External port for phpMyAdmin (dev only)
- `DB_PORT_EXTERNAL` - External port for database

## 🔒 Security Notes

### Development
- ✅ Weak passwords OK
- ✅ Debug mode enabled
- ✅ phpMyAdmin exposed

### Production
- ❌ **MUST change all passwords**
- ❌ Debug mode disabled
- ❌ No phpMyAdmin exposed
- ✅ SSL certificates (if using .env.ssl)

## 🚨 Important Reminders

1. **Never commit real .env files** - they contain secrets
2. **Always change passwords** before production deployment
3. **Use .env.ssl for production** - it includes SSL setup
4. **Test locally first** with .env.example → .env

## 🔍 Validation

Check your configuration:
```bash
# Verify environment is loaded
docker compose exec php-app env | grep APP_

# Check database connection
docker compose exec mariadb mysql -u$DB_USER -p$DB_PASS -e "SELECT 1"

# Test SSL (production only)
curl -I https://yourdomain.com
```