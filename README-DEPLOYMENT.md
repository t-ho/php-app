# Deployment Guide

Complete guide to deploy the PHP App in development and production with SSL.

## 🚀 Quick Start

### Development (Local)
```bash
./scripts/dev.sh
```
- Includes phpMyAdmin at http://localhost:8081
- Debug mode enabled
- Sample data loaded

### Production (No SSL)
```bash
./scripts/prod.sh
```
- No phpMyAdmin (security)
- Debug mode disabled
- HTTP only

### Production (With SSL) - **Recommended**
```bash
./scripts/deploy-ssl-apache.sh
```
- Automatic Let's Encrypt SSL certificates
- HTTPS with security headers
- Production optimized

## 📁 Environment Files

### .env.development
- Debug enabled
- phpMyAdmin included
- Development ports (8080, 8081)
- Weak passwords (OK for dev)

### .env.production
- Debug disabled
- No phpMyAdmin
- HTTP only (port 80)
- **CHANGE ALL PASSWORDS** before deploying

### .env.ssl
- Production settings with SSL
- Let's Encrypt configuration
- **CHANGE DOMAIN AND PASSWORDS** before deploying

## 🌊 Complete Production Deployment Guide

### Option A: DigitalOcean with SSL (Recommended)

1. **Create DigitalOcean Droplet**
   - Ubuntu 22.04 LTS
   - At least 1GB RAM (2GB recommended)
   - Enable Docker during creation

2. **Configure Domain DNS**
   ```bash
   # Point your domain to droplet IP
   # A Record: yourdomain.com → YOUR_DROPLET_IP
   ```

3. **Upload and Configure**
   ```bash
   # Upload project to server
   scp -r . root@YOUR_DROPLET_IP:/var/www/php-app/
   
   # SSH into server
   ssh root@YOUR_DROPLET_IP
   cd /var/www/php-app
   
   # Configure SSL environment
   nano .env.ssl
   ```
   
   **Edit these required fields:**
   ```bash
   DOMAIN=yourdomain.com
   SSL_EMAIL=your-email@domain.com
   
   # Also change all passwords:
   DB_PASS=your_strong_db_password
   MYSQL_ROOT_PASSWORD=your_strong_root_password
   ```

4. **Deploy with SSL**
   ```bash
   ./scripts/deploy-ssl-apache.sh
   ```

5. **Verify Deployment**
   - ✅ App: `https://yourdomain.com`
   - ✅ SSL certificate active
   - ✅ HTTP redirects to HTTPS

### Option B: Any Server with SSL

1. **Server Requirements**
   - Ubuntu/Debian/CentOS with root access
   - Docker & Docker Compose installed
   - Ports 80 and 443 open

2. **Domain Setup**
   ```bash
   # Point DNS A record: yourdomain.com → YOUR_SERVER_IP
   ```

3. **Upload Files**
   ```bash
   scp -r . root@YOUR_SERVER_IP:/var/www/php-app/
   ```

4. **Configure and Deploy**
   ```bash
   ssh root@YOUR_SERVER_IP
   cd /var/www/php-app
   
   # Edit SSL configuration
   nano .env.ssl  # Change DOMAIN, SSL_EMAIL, and passwords
   
   # Deploy with SSL
   ./scripts/deploy-ssl-apache.sh
   ```

### Option C: HTTP Only (Not Recommended)

```bash
# On your server
cd /var/www/php-app
cp .env.production .env
nano .env  # Change passwords
docker-compose -f docker-compose.prod.yml up -d
```

## 🔒 Security Checklist

### SSL Deployment (Recommended)
- [ ] Domain DNS pointing to server
- [ ] Changed DOMAIN and SSL_EMAIL in .env.ssl
- [ ] Changed all passwords in .env.ssl
- [ ] SSL certificates obtained and working
- [ ] HTTPS redirect working
- [ ] Security headers active

### Server Security
- [ ] Firewall configured (ufw enable)
- [ ] SSH key authentication only
- [ ] Non-root user for SSH (optional)
- [ ] Regular security updates
- [ ] Database backups configured

### Application Security
- [ ] APP_DEBUG=false in production
- [ ] Strong database passwords
- [ ] Regular Docker image updates

## 🗄️ Database Access

### Development
- phpMyAdmin: http://localhost:8081
- Direct: localhost:3306
- Credentials: db_user / db_password

### Production
**No direct access for security**

For maintenance, use SSH tunnel:
```bash
ssh -L 3306:localhost:3306 root@YOUR_SERVER_IP
# Then connect to localhost:3306 locally
```

Or use Docker exec:
```bash
docker exec -it php-app-mariadb mysql -u db_prod_user -p
```

## 🔧 Troubleshooting

### SSL Certificate Issues
```bash
# Check certificate status
docker logs certbot

# Check Apache SSL logs
docker logs php-app

# Verify domain DNS
nslookup yourdomain.com

# Test certificate manually
openssl s_client -connect yourdomain.com:443 -servername yourdomain.com
```

### Application Issues
```bash
# Check application logs
docker logs php-app

# Check database logs
docker logs php-app-mariadb

# Restart services
docker-compose -f docker-compose.ssl-apache.yml restart
```

### Complete Reset
```bash
# Stop everything and remove data
docker-compose -f docker-compose.ssl-apache.yml down -v

# Redeploy
./scripts/deploy-ssl-apache.sh
```

## 🚀 Post-Deployment Steps

1. **Set up certificate renewal**
   ```bash
   # Add to crontab
   crontab -e
   
   # Add this line:
   0 12 * * * cd /var/www/php-app && docker-compose -f docker-compose.ssl-apache.yml run --rm certbot renew && docker-compose -f docker-compose.ssl-apache.yml restart php-app
   ```

2. **Configure backups**
   ```bash
   # Database backup script
   docker exec php-app-mariadb mysqldump -u root -p$MYSQL_ROOT_PASSWORD app_db_prod > backup.sql
   ```

3. **Monitor application**
   - Set up server monitoring
   - Monitor SSL certificate expiry
   - Regular security updates

## 📞 Support

If you encounter issues:
1. Check the troubleshooting section above
2. Verify all prerequisites are met
3. Check server logs and DNS configuration