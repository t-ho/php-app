# SSL Deployment Guide

Deploy your PHP app with automatic Let's Encrypt SSL certificates using Apache + Certbot.

## 🎯 Quick Start

```bash
./scripts/deploy-ssl-apache.sh
```

## 📋 Prerequisites

1. **Domain Setup**
   - Domain pointing to your server IP
   - DNS A record: `yourdomain.com` → `your-server-ip`

2. **Server Requirements**
   - Ports 80 and 443 open
   - Docker and Docker Compose installed

3. **Configuration**
   ```bash
   # Edit SSL environment file
   nano .env.ssl
   ```
   
   Update these required fields:
   ```bash
   DOMAIN=yourdomain.com
   SSL_EMAIL=your-email@domain.com
   ```

## 📖 Setup

**Architecture:** `Internet → Apache (with SSL) → PHP app`

```bash
# 1. Configure domain  
nano .env.ssl  # Change DOMAIN and SSL_EMAIL

# 2. Deploy
./scripts/deploy-ssl-apache.sh

# 3. Access your app
https://yourdomain.com
```

**Features:**
- Direct Apache SSL
- Built-in security headers
- Automatic HTTP → HTTPS redirect

## 🔄 Certificate Renewal

Add to crontab for automatic renewal:
```bash
0 12 * * * cd /path/to/app && docker-compose -f docker-compose.ssl-apache.yml run --rm certbot renew && docker-compose restart php-app
```

## 🚨 Troubleshooting

### Certificate Failed
```bash
# Check DNS
nslookup yourdomain.com

# Check ports
sudo netstat -tlnp | grep :80
sudo netstat -tlnp | grep :443

# Check logs
docker logs nginx-ssl
docker logs certbot
```

### HTTP Still Accessible
- Check if redirect is working
- Verify SSL certificate installation
- Check firewall settings

### Apache SSL Issues
```bash
# Check Apache SSL logs
docker logs php-app

# Check certificate files
docker exec php-app ls -la /etc/letsencrypt/live/

# Test SSL configuration
docker exec php-app apache2ctl -S
```

## 🔒 Security Features

Includes:
- ✅ HTTP to HTTPS redirect
- ✅ Security headers (HSTS, CSP, etc.)
- ✅ TLS 1.2+ only
- ✅ Strong cipher suites
- ✅ OCSP stapling

## 🌐 Production Checklist

- [ ] Domain DNS configured
- [ ] Firewall ports 80/443 open
- [ ] Strong passwords in .env.ssl
- [ ] SSL certificates obtained
- [ ] HTTP redirects to HTTPS
- [ ] Certificate auto-renewal set up
- [ ] Backup strategy in place