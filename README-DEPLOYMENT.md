# Deployment Guide

This guide explains how to deploy the PHP App application in different environments.

## Quick Start

### Development
```bash
./scripts/dev.sh
```
- Includes phpMyAdmin at http://localhost:8081
- Debug mode enabled
- Sample data loaded

### Production
```bash
./scripts/prod.sh
```
- No phpMyAdmin (security)
- Debug mode disabled
- Only schema loaded (no sample data)

## Environment Files

### .env.development
- Debug enabled
- phpMyAdmin included
- Development ports (8080, 8081)
- Weak passwords (OK for dev)

### .env.production
- Debug disabled
- No phpMyAdmin
- Production port (80)
- **CHANGE ALL PASSWORDS** before deploying

## DigitalOcean Deployment

1. **Create Droplet**
   - Ubuntu 22.04 with Docker
   - At least 1GB RAM

2. **Update Settings**
   ```bash
   # Edit .env.production with strong passwords
   nano .env.production
   
   # Edit deploy script with your droplet IP
   nano scripts/deploy-digitalocean.sh
   ```

3. **Deploy**
   ```bash
   ./scripts/deploy-digitalocean.sh
   ```

## Manual Production Setup

1. **Server Requirements**
   - Docker & Docker Compose
   - Ubuntu/Debian/CentOS

2. **Upload Files**
   ```bash
   scp -r . user@server:/var/www/php-app/
   ```

3. **Deploy**
   ```bash
   cd /var/www/php-app
   cp .env.production .env
   docker-compose -f docker-compose.prod.yml up -d
   ```

## Security Checklist

- [ ] Change all passwords in .env.production
- [ ] Set APP_DEBUG=false in production
- [ ] Configure SSL certificates
- [ ] Set up firewall (ufw)
- [ ] Regular database backups
- [ ] Update Docker images regularly

## Database Access

### Development
- phpMyAdmin: http://localhost:8081
- Direct: localhost:3306

### Production
- SSH tunnel: `ssh -L 3306:localhost:3306 user@server`
- Or use managed database service

## Troubleshooting

### Check logs
```bash
docker logs php-app
docker logs php-app-mariadb
```

### Restart services
```bash
docker-compose restart
```

### Reset database
```bash
docker-compose down -v
docker-compose up -d
```