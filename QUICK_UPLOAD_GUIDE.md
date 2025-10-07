# 🚀 Quick Upload Guide - Laravel E-Commerce to Domain

## 📋 Overview

This is a simplified guide to quickly upload your Laravel e-commerce project to a domain. Choose the method that best fits your hosting situation.

## 🎯 Quick Start Options

### Option 1: Shared Hosting (cPanel/WHM) - Easiest
### Option 2: VPS/Cloud Server - Most Control
### Option 3: Managed Laravel Hosting - Most Convenient

---

## 🏠 Option 1: Shared Hosting (cPanel/WHM)

### Step 1: Prepare Your Project

**On your local computer:**

```bash
# Run the upload preparation script
php upload-to-hosting.php
```

This will create:
- `ecommerce-core1-upload.zip` - Ready-to-upload package
- `DEPLOYMENT_INSTRUCTIONS.txt` - Step-by-step instructions

### Step 2: Upload to Hosting

1. **Login to cPanel**
2. **Open File Manager**
3. **Navigate to public_html** (or your domain folder)
4. **Upload the zip file**
5. **Extract the zip file**
6. **Move all contents** from extracted folder to public_html

### Step 3: Database Setup

1. **Create Database in cPanel:**
   - Go to "MySQL Databases"
   - Create new database: `ecommerce_core1`
   - Create user: `ecommerce_user`
   - Add user to database with all privileges

2. **Update .env file:**
   ```env
   DB_DATABASE=ecommerce_core1
   DB_USERNAME=ecommerce_user
   DB_PASSWORD=your_strong_password
   ```

### Step 4: Run Setup Commands

**In cPanel Terminal (or SSH if available):**

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Generate API keys
php artisan api:generate --name="Core Transaction 1 Export" --permissions=core_transaction_1_export
php artisan api:generate --name="Core Transaction 2" --permissions=product_management,shared_data
php artisan api:generate --name="Core Transaction 3" --permissions=platform_management,shared_data

# Set up data sharing
php setup_core_transaction_1_data_sharing.php

# Create storage link
php artisan storage:link
```

### Step 5: Test Your Site

Visit your domain: `https://yourdomain.com`

---

## 🖥️ Option 2: VPS/Cloud Server

### Step 1: Server Setup

**Connect to your server via SSH:**

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-bcmath php8.1-gd php8.1-cli composer nodejs npm git unzip -y
```

### Step 2: Upload Your Project

**Method A: Git Clone (Recommended)**
```bash
cd /var/www
sudo git clone https://github.com/yourusername/ecommerce-core1.git
sudo chown -R www-data:www-data ecommerce-core1
cd ecommerce-core1
```

**Method B: Upload via SCP/SFTP**
```bash
# From your local computer
scp -r /path/to/your/project user@your-server-ip:/var/www/ecommerce-core1
```

### Step 3: Run Deployment Script

```bash
# Make script executable
chmod +x deploy.sh

# Run deployment
./deploy.sh
```

### Step 4: Configure Web Server

**Nginx Configuration:**
```bash
sudo nano /etc/nginx/sites-available/ecommerce-core1
```

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/ecommerce-core1/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/ecommerce-core1 /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Step 5: SSL Certificate

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Get SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

---

## ☁️ Option 3: Managed Laravel Hosting

### Laravel Forge (Recommended)

1. **Sign up at [Laravel Forge](https://forge.laravel.com)**
2. **Connect your server** (DigitalOcean, AWS, Linode, etc.)
3. **Create new site:**
   - Domain: `yourdomain.com`
   - Directory: `/home/forge/yourdomain.com`
4. **Deploy from Git:**
   - Repository: `https://github.com/yourusername/ecommerce-core1.git`
   - Branch: `main`
5. **Configure environment variables** in Forge dashboard
6. **Deploy!**

### Laravel Vapor (AWS)

1. **Install Vapor CLI:**
   ```bash
   composer global require laravel/vapor-cli
   ```

2. **Initialize Vapor:**
   ```bash
   vapor init
   ```

3. **Deploy:**
   ```bash
   vapor deploy production
   ```

---

## 🔧 Quick Configuration

### Essential .env Settings

```env
# Application
APP_NAME="E-Commerce Core 1"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_core1
DB_USERNAME=ecommerce_user
DB_PASSWORD=your_strong_password

# Mail (Update with your mail service)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Core Transaction Data Sharing
CORE_TRANSACTION_2_WEBHOOK_URL=https://core-transaction-2.yourdomain.com/webhooks/ecommerce-updates
CORE_TRANSACTION_2_WEBHOOK_SECRET=your_secret_key
CORE_TRANSACTION_3_WEBHOOK_URL=https://core-transaction-3.yourdomain.com/webhooks/ecommerce-updates
CORE_TRANSACTION_3_WEBHOOK_SECRET=your_secret_key

# Payment Gateways (Update with your credentials)
STRIPE_KEY=pk_live_your_stripe_key
STRIPE_SECRET=sk_live_your_stripe_secret
```

---

## 🧪 Testing Your Deployment

### 1. Basic Functionality
- Visit: `https://yourdomain.com`
- Check if the homepage loads
- Test navigation and basic features

### 2. API Endpoints
- Test: `https://yourdomain.com/api/core-transaction-1/stats`
- Should return JSON with statistics

### 3. Webhook Testing
- Test: `https://yourdomain.com/api/core-transaction-1/webhooks/test`
- Should return success message

### 4. Database Connection
- Try creating an account
- Check if data is saved to database

---

## 🆘 Common Issues & Solutions

### Issue 1: 500 Internal Server Error
**Solution:**
```bash
# Check logs
tail -f storage/logs/laravel.log

# Fix permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Issue 2: Database Connection Error
**Solution:**
- Check database credentials in .env
- Ensure database exists and user has permissions
- Test connection: `php artisan tinker` then `DB::connection()->getPdo()`

### Issue 3: File Not Found (404)
**Solution:**
- Check web server configuration
- Ensure document root points to `public` directory
- Verify .htaccess file exists in public directory

### Issue 4: Permission Denied
**Solution:**
```bash
# Fix file permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache
```

---

## 📋 Post-Deployment Checklist

- [ ] Domain is accessible via HTTPS
- [ ] All pages load correctly
- [ ] Database connection works
- [ ] API endpoints respond
- [ ] Webhook testing works
- [ ] File uploads work (if applicable)
- [ ] Email sending works (if configured)
- [ ] SSL certificate is valid
- [ ] Performance is acceptable

---

## 🎉 Success!

Your Laravel e-commerce project with Core Transaction 1, 2, and 3 data sharing capabilities is now live!

### Next Steps:
1. **Test all functionality** thoroughly
2. **Configure monitoring** and backups
3. **Set up domain email** (if needed)
4. **Monitor performance** and optimize
5. **Keep the system updated**

---

## 📚 Additional Resources

- **DEPLOYMENT_GUIDE.md** - Complete deployment guide
- **CORE_TRANSACTION_1_DATA_SHARING_GUIDE.md** - Data sharing guide
- **CORE_TRANSACTION_1_SUMMARY.md** - Complete solution overview

**Need Help?** Check the troubleshooting section or refer to the comprehensive documentation! 🚀
