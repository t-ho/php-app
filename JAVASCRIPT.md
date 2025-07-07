# JavaScript Build System

This PHP App uses **Vite** for modern JavaScript development with hot reload, bundling, and cache busting.

## Quick Start

### 🚀 **Development with Hot Reload**

```bash
# One command - everything starts automatically! 🎉
sudo docker compose up -d

# Or use the helper script
npm start
```

**That's it!** Everything starts automatically:
- ✅ **PHP app** with hot reload support
- ✅ **Vite dev server** with live JavaScript updates  
- ✅ **MariaDB** database
- ✅ **phpMyAdmin** for database management

**Alternative options:**
```bash
# Using existing scripts
npm run docker:dev    # PHP app via script

# Mixed setup (advanced)
sudo docker compose up -d php-app mariadb phpmyadmin
npm install && npm run dev

# Full local development  
npm install && npm run dev
php -S localhost:8000 -t public
```

### 🏗️ **Production Build**

```bash
# Build assets and deploy
npm run docker:prod   # Full production deployment
npm run docker:ssl    # SSL deployment with Let's Encrypt

# Just build assets
npm install && npm run build
```

## How It Works

### **Two-Server Architecture**
- **PHP App** (`localhost:8080`) - Your main application
- **Vite Dev Server** (`localhost:3000`) - JavaScript hot reload

### **Smart Asset Loading**
- **Development**: Loads JS from Vite dev server with hot reload
- **Production**: Loads pre-built, hashed assets from `/dist/`

### **Entry Points**
- `assets/js/main.js` - Public pages (date formatting)
- `assets/js/admin.js` - Admin pages (TinyMCE + date formatting)

### **Modular Structure**
```
assets/js/
├── main.js                    # Public entry point
├── admin.js                   # Admin entry point
└── modules/
    ├── date-formatter.js      # Date/datetime formatting
    └── tinymce-editor.js      # Rich text editor
```

## Environment Variables

Add to your `.env` file:

```env
# Development
APP_ENV=development
VITE_DEV_SERVER=http://localhost:3000

# For Docker
VITE_DEV_SERVER=http://host.docker.internal:3000
```

## Scripts

### **Development**
- `npm run dev` - Start Vite dev server
- `npm run docker:dev` - Start PHP app via Docker
- `npm run docker:vite` - Start Vite in Docker container

### **Production**
- `npm run build` - Build production assets
- `npm run docker:prod` - Full production deployment
- `npm run docker:ssl` - SSL deployment

### **Utilities**
- `npm run docker:stop` - Stop all containers
- `npm run docker:logs` - View container logs

## Adding New JavaScript Modules

1. **Create your module** in `assets/js/modules/`
2. **Import and use** in `main.js` or `admin.js`
3. **No configuration needed** - Vite handles everything automatically

Example:
```javascript
// assets/js/modules/my-feature.js
export class MyFeature {
  init() {
    console.log('Feature loaded!');
  }
}

// assets/js/main.js
import { MyFeature } from './modules/my-feature.js';
const feature = new MyFeature();
feature.init();
```

## Troubleshooting

### **JavaScript not loading?**
1. Check if you're in development mode: visit `http://localhost:3000`
2. If Vite not running, install deps: `npm install`
3. Build production assets: `npm run build`

### **Hot reload not working?**
1. Ensure Vite dev server is running on port 3000
2. Check browser console for CORS errors
3. Verify `APP_ENV=development` in your `.env`

### **Docker issues?**

**Services not starting:**
```bash
# Check logs
sudo docker compose logs vite
sudo docker compose logs php-app

# Rebuild containers
sudo docker compose down && sudo docker compose up --build
```

**Port 3000 not accessible:**
```bash
# Check if Vite container is running
sudo docker compose ps

# Check what's using port 3000
sudo lsof -i :3000

# Restart Vite service
sudo docker compose restart vite
```

**Permission denied with Docker:**
```bash
# Add your user to docker group (requires logout/login)
sudo usermod -aG docker $USER

# Or use sudo with all docker commands
sudo docker compose up -d
```

**Hot reload not working:**
```bash
# Check browser console for connection errors
# Verify APP_ENV=development in container
sudo docker compose exec php-app env | grep APP_ENV

# Check Vite dev server logs
sudo docker compose logs vite
```

## Benefits

✅ **Hot Reload** - See changes instantly  
✅ **Modern ES Modules** - Clean, maintainable code  
✅ **Automatic Optimization** - Minification, tree-shaking  
✅ **Cache Busting** - Hashed filenames in production  
✅ **Legacy Support** - Works in older browsers  
✅ **No Configuration** - Add modules without config changes