# PHP App OOP

A modern, object-oriented PHP application with Docker support, modern JavaScript tooling, and comprehensive deployment options.

## ✨ Features

### 🏗️ **Core Features**
- **Object-Oriented PHP 8.2** - Modern PHP with type declarations
- **MVC Architecture** - Clean separation of concerns
- **Custom Router** - RESTful routing with middleware support
- **Database Migrations** - Phinx-powered schema management
- **User Authentication** - Login, registration, "Remember Me"
- **Rich Text Editor** - TinyMCE with image uploads
- **CSRF Protection** - Built-in security middleware
- **Input Sanitization** - HTMLPurifier integration

### 🔥 **Modern JavaScript**
- **Vite Build System** - Fast development with hot reload
- **ES6 Modules** - Clean, modular JavaScript architecture
- **Hot Reload** - See changes instantly without browser refresh
- **Automatic Cache Busting** - Hashed filenames in production
- **Legacy Browser Support** - Works across all modern browsers

### 🐳 **Docker & Deployment**
- **Multi-Environment Setup** - Development, production, SSL
- **One-Command Development** - Everything starts automatically
- **Let's Encrypt SSL** - Automatic HTTPS certificates
- **Production Optimized** - Multi-stage builds, security hardened
- **Database Management** - phpMyAdmin in development

## 🚀 Quick Start

### **Development (One Command)**
```bash
# Clone and start everything
git clone <your-repo>
cd php-app-oop
sudo docker compose up -d
```

**Automatically starts:**
- ✅ **PHP app with hot reload** - `http://localhost:8080`
- ✅ **Vite dev server** - `http://localhost:3000` 
- ✅ **Database & phpMyAdmin** - `http://localhost:8081`

### **Production Deployment**
```bash
# SSL deployment (recommended)
nano .env.ssl  # Change DOMAIN, SSL_EMAIL, passwords
./scripts/deploy-ssl-apache.sh

# HTTP-only deployment
./scripts/prod.sh
```

## 📁 Project Structure

```
├── app/
│   ├── Controllers/         # Request handlers
│   ├── Core/               # Framework core (Router, Database, etc.)
│   ├── Helpers/            # Helper functions and utilities
│   ├── Models/             # Data models
│   ├── Services/           # Business logic services
│   └── Views/              # PHP templates
├── assets/
│   └── js/                 # Modern JavaScript source
│       ├── main.js         # Public pages entry
│       ├── admin.js        # Admin pages entry
│       └── modules/        # Reusable JS modules
├── database/
│   ├── migrations/         # Database schema changes
│   └── seeds/              # Sample data
├── public/
│   ├── dist/               # Built JavaScript assets (auto-generated)
│   └── uploads/            # User uploaded files
├── scripts/                # Deployment scripts
├── docker-compose.yml      # Development stack
└── vite.config.js          # JavaScript build configuration
```

## 🛠️ Development Workflow

### **JavaScript Development**
```bash
# Edit any file in assets/js/
# Changes appear instantly with hot reload!

# Add new modules
mkdir assets/js/modules/my-feature.js
# Import in main.js or admin.js - no config needed
```

### **PHP Development**
```bash
# Traditional PHP workflow
# Edit controllers, models, views
# Refresh browser to see changes
```

### **Database Changes**
```bash
# Create migration
docker exec php-app vendor/bin/phinx create YourMigrationName

# Run migrations
docker exec php-app vendor/bin/phinx migrate

# Add sample data
docker exec php-app vendor/bin/phinx seed:run
```

## 📚 Documentation

| File | Purpose |
|------|---------|
| [JAVASCRIPT.md](JAVASCRIPT.md) | Complete JavaScript/Vite development guide |
| [README-DEPLOYMENT.md](README-DEPLOYMENT.md) | Production deployment guide |
| [README-ENV.md](README-ENV.md) | Environment configuration guide |

## 🎯 Available Scripts

### **Development**
```bash
npm start                    # Start everything (PHP + Vite + DB)
sudo docker compose up -d    # Manual Docker startup
npm run dev                  # Vite dev server only
```

### **Deployment**
```bash
npm run docker:dev          # Development environment
npm run docker:prod         # Production deployment  
npm run docker:ssl          # SSL deployment
```

### **Assets**
```bash
npm run build               # Build production JavaScript
npm run watch               # Build and watch for changes
```

### **Utilities**
```bash
npm run docker:stop         # Stop all containers
npm run docker:logs         # View container logs
```

## 🌍 Access Points

### **Development**
- **Main Application**: http://localhost:8080
- **Vite Dev Server**: http://localhost:3000
- **phpMyAdmin**: http://localhost:8081
  - Username: `blog_user` 
  - Password: `blog_password`

### **Production** 
- **Your Domain**: https://yourdomain.com (after SSL setup)

## 🔧 System Requirements

### **Development**
- Docker & Docker Compose
- Node.js 20+ (for local Vite development)
- Git

### **Production Server**
- Ubuntu/Debian/CentOS with Docker
- Domain name pointing to server IP
- Ports 80 and 443 open

## 🏃‍♂️ Getting Started

### **1. Clone & Start**
```bash
git clone <your-repo>
cd php-app-oop
sudo docker compose up -d
```

### **2. Add Sample Data**
```bash
docker exec php-app vendor/bin/phinx seed:run

# And migrate
docker exec php-app vendor/bin/phinx migrate
```

### **3. Start Developing**
- **Edit PHP**: Changes visible on browser refresh
- **Edit JavaScript**: Changes appear instantly with hot reload
- **Visit**: http://localhost:8080 to see your app

### **4. Deploy to Production**
```bash
# Configure domain and SSL
nano .env.ssl

# Deploy with HTTPS
./scripts/deploy-ssl-apache.sh
```

## 🆘 Troubleshooting

### **JavaScript not loading?**
```bash
# Check if Vite is running
curl http://localhost:3000

# Check container logs
sudo docker compose logs vite

# Rebuild if needed
sudo docker compose build vite --no-cache
```

### **Port conflicts?**
```bash
# Check what's using ports
sudo lsof -i :8080
sudo lsof -i :3000

# Change ports in docker-compose.yml if needed
```

### **Database issues?**
```bash
# Check database logs
sudo docker compose logs mariadb

# Reset database
sudo docker compose down -v
sudo docker compose up -d

# seeding
docker exec php-app vendor/bin/phinx seed:run

# Migrating
docker exec php-app vendor/bin/phinx migrate
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test with `sudo docker compose up -d`
5. Submit a pull request

## 📄 License

This project is open source. Please check the license file for details.

## 🙋‍♂️ Support

- 📖 Check the documentation files in this repository
- 🐛 Report issues via GitHub Issues
- 💬 Questions? Check the troubleshooting sections in the documentation

---

**Happy coding!** 🎉