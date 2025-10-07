# 🚀 Laravel E-Commerce Project Deployment Guide

## 📋 Overview

This guide will walk you through deploying your Laravel e-commerce project with Core Transaction 1, 2, and 3 data sharing capabilities to a live domain.

## 🏗️ Pre-Deployment Checklist

### 1. Project Preparation
- [ ] All code is committed to version control
- [ ] Database migrations are ready
- [ ] Environment configuration is complete
- [ ] API keys are generated
- [ ] Webhook URLs are configured
- [ ] All dependencies are installed

### 2. Server Requirements
- **PHP**: 8.1 or higher
- **Composer**: Latest version
- **Node.js**: 16.x or higher (for frontend assets)
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **SSL Certificate**: For HTTPS
- **Domain**: Configured and pointing to server

## 🖥️ Server Setup Options

### Option 1: Shared Hosting (cPanel/WHM)
### Option 2: VPS/Cloud Server (DigitalOcean, AWS, Linode)
### Option 3: Managed Laravel Hosting (Laravel Forge, Vapor)

## 📦 Method 1: Shared Hosting Deployment

### Step 1: Prepare Your Project

```bash
# 1. Create production build
npm run build

# 2. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Create deployment package
zip -r ecommerce-deployment.zip . -x "node_modules/*" ".git/*" "tests/*" "*.md"
```

### Step 2: Upload to Server

1. **Access cPanel File Manager**
2. **Navigate to public_html** (or your domain folder)
3. **Upload the zip file**
4. **Extract the files**
5. **Move contents** from extracted folder to public_html

### Step 3: Configure Files

```bash
# 1. Set proper permissions
chmod -R 755 storage bootstrap/cache
chmod -R 644 .env

# 2. Create .env file
cp .env.example .env
```

### Step 4: Database Setup

1. **Create database** in cPanel
2. **Create database user** with full privileges
3. **Update .env file** with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### Step 5: Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies (if needed)
npm install --production
```

### Step 6: Run Migrations

```bash
# Run database migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force
```

## 🌐 Method 2: VPS/Cloud Server Deployment

### Step 1: Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-bcmath php8.1-gd php8.1-cli composer nodejs npm git unzip -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Step 2: Database Setup

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE ecommerce_core1;
CREATE USER 'ecommerce_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON ecommerce_core1.* TO 'ecommerce_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Deploy Application

```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/yourusername/ecommerce-core1.git
sudo chown -R www-data:www-data ecommerce-core1
cd ecommerce-core1

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install --production
npm run build

# Set permissions
sudo chmod -R 755 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Step 4: Configure Nginx

```bash
# Create Nginx configuration
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

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/ecommerce-core1 /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Step 5: Configure PHP-FPM

```bash
# Edit PHP-FPM configuration
sudo nano /etc/php/8.1/fpm/pool.d/www.conf
```

```ini
user = www-data
group = www-data
listen = /var/run/php/php8.1-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660
```

```bash
# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

### Step 6: SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

## 🔧 Environment Configuration

### Step 1: Create Production .env

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 2: Configure .env for Production

```env
APP_NAME="E-Commerce Core 1"
APP_ENV=production
APP_KEY=base64:your_generated_key
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_core1
DB_USERNAME=ecommerce_user
DB_PASSWORD=your_strong_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Core Transaction 1 Data Sharing Configuration
CORE_TRANSACTION_2_WEBHOOK_URL=https://core-transaction-2.yourdomain.com/webhooks/ecommerce-updates
CORE_TRANSACTION_2_WEBHOOK_SECRET=your_core_transaction_2_secret
CORE_TRANSACTION_3_WEBHOOK_URL=https://core-transaction-3.yourdomain.com/webhooks/ecommerce-updates
CORE_TRANSACTION_3_WEBHOOK_SECRET=your_core_transaction_3_secret
WEBHOOK_TIMEOUT=30
WEBHOOK_RETRY_ATTEMPTS=3
WEBHOOK_RETRY_DELAY=5

# Payment Gateway Configuration
STRIPE_KEY=pk_live_your_stripe_key
STRIPE_SECRET=sk_live_your_stripe_secret
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

# Shipping Configuration
JNT_API_URL=https://api.jtexpress.com.ph
JNT_API_KEY=your_jnt_api_key
NINJAVAN_API_URL=https://api.ninjavan.co
NINJAVAN_API_KEY=your_ninjavan_api_key
```

### Step 3: Run Production Setup

```bash
# Clear and cache configuration
php artisan config:clear
php artisan config:cache

# Cache routes and views
php artisan route:cache
php artisan view:cache

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

# Set proper permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache
```

## 🔐 Security Configuration

### Step 1: File Permissions

```bash
# Set proper file permissions
sudo find /var/www/ecommerce-core1 -type f -exec chmod 644 {} \;
sudo find /var/www/ecommerce-core1 -type d -exec chmod 755 {} \;
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Step 2: Firewall Configuration

```bash
# Configure UFW firewall
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

### Step 3: Database Security

```sql
-- Remove test database
DROP DATABASE IF EXISTS test;

-- Remove anonymous users
DELETE FROM mysql.user WHERE User='';

-- Remove remote root access
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');

-- Flush privileges
FLUSH PRIVILEGES;
```

## 📊 Performance Optimization

### Step 1: PHP Optimization

```bash
# Edit PHP configuration
sudo nano /etc/php/8.1/fpm/php.ini
```

```ini
# Performance settings
memory_limit = 256M
max_execution_time = 300
max_input_vars = 3000
upload_max_filesize = 64M
post_max_size = 64M

# OPcache settings
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### Step 2: Nginx Optimization

```bash
# Edit Nginx configuration
sudo nano /etc/nginx/nginx.conf
```

```nginx
# Add to http block
gzip on;
gzip_vary on;
gzip_min_length 1024;
gzip_proxied any;
gzip_comp_level 6;
gzip_types
    text/plain
    text/css
    text/xml
    text/javascript
    application/json
    application/javascript
    application/xml+rss
    application/atom+xml
    image/svg+xml;
```

### Step 3: Database Optimization

```sql
-- Add indexes for better performance
ALTER TABLE users ADD INDEX idx_email (email);
ALTER TABLE orders ADD INDEX idx_user_id (user_id);
ALTER TABLE orders ADD INDEX idx_status (status);
ALTER TABLE products ADD INDEX idx_seller_id (seller_id);
ALTER TABLE products ADD INDEX idx_category (category);
ALTER TABLE reviews ADD INDEX idx_product_id (product_id);
```

## 🔄 Automated Deployment

### Step 1: Create Deployment Script

```bash
# Create deployment script
nano deploy.sh
```

```bash
#!/bin/bash

# Deployment script for Laravel E-Commerce
echo "🚀 Starting deployment..."

# Pull latest changes
git pull origin main

# Install/update dependencies
composer install --optimize-autoloader --no-dev
npm install --production
npm run build

# Clear and cache configuration
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache

# Restart services
sudo systemctl reload nginx
sudo systemctl restart php8.1-fpm

echo "✅ Deployment completed successfully!"
```

```bash
# Make script executable
chmod +x deploy.sh
```

### Step 2: Set up Cron Jobs

```bash
# Edit crontab
crontab -e
```

```cron
# Laravel scheduler
* * * * * cd /var/www/ecommerce-core1 && php artisan schedule:run >> /dev/null 2>&1

# Database backups (daily at 2 AM)
0 2 * * * mysqldump -u ecommerce_user -p'password' ecommerce_core1 > /var/backups/ecommerce_$(date +\%Y\%m\%d).sql

# Log rotation (weekly)
0 0 * * 0 find /var/www/ecommerce-core1/storage/logs -name "*.log" -mtime +7 -delete
```

## 📱 Domain Configuration

### Step 1: DNS Configuration

```
# A Records
yourdomain.com → YOUR_SERVER_IP
www.yourdomain.com → YOUR_SERVER_IP

# CNAME Records (if using subdomains)
api.yourdomain.com → yourdomain.com
admin.yourdomain.com → yourdomain.com
```

### Step 2: Subdomain Setup

```bash
# Create subdomain configurations
sudo nano /etc/nginx/sites-available/api.yourdomain.com
```

```nginx
server {
    listen 80;
    server_name api.yourdomain.com;
    root /var/www/ecommerce-core1/public;
    
    # API-specific configuration
    location /api {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # Rest of configuration same as main domain
}
```

## 🧪 Testing Deployment

### Step 1: Basic Functionality Test

```bash
# Test application
curl -I https://yourdomain.com

# Test API endpoints
curl -H "X-API-Key: your_api_key" https://yourdomain.com/api/core-transaction-1/stats

# Test webhook endpoints
curl -X POST https://yourdomain.com/api/core-transaction-1/webhooks/test
```

### Step 2: Performance Test

```bash
# Install Apache Bench
sudo apt install apache2-utils -y

# Test performance
ab -n 1000 -c 10 https://yourdomain.com/
```

## 📊 Monitoring Setup

### Step 1: Log Monitoring

```bash
# Install log monitoring
sudo apt install logwatch -y

# Configure log monitoring
sudo nano /etc/logwatch/conf/logwatch.conf
```

### Step 2: Application Monitoring

```bash
# Install monitoring tools
sudo apt install htop iotop nethogs -y

# Set up log rotation
sudo nano /etc/logrotate.d/ecommerce-core1
```

```
/var/www/ecommerce-core1/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 www-data www-data
}
```

## 🆘 Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   ```bash
   # Check logs
   tail -f /var/log/nginx/error.log
   tail -f /var/www/ecommerce-core1/storage/logs/laravel.log
   
   # Check permissions
   sudo chown -R www-data:www-data /var/www/ecommerce-core1
   sudo chmod -R 755 /var/www/ecommerce-core1
   ```

2. **Database Connection Error**
   ```bash
   # Test database connection
   mysql -u ecommerce_user -p ecommerce_core1
   
   # Check .env configuration
   php artisan config:show database
   ```

3. **Permission Denied**
   ```bash
   # Fix permissions
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```

## 📋 Post-Deployment Checklist

- [ ] Domain is accessible via HTTPS
- [ ] All API endpoints are working
- [ ] Database is properly configured
- [ ] SSL certificate is valid
- [ ] File permissions are correct
- [ ] Cron jobs are set up
- [ ] Monitoring is configured
- [ ] Backups are scheduled
- [ ] Performance is optimized
- [ ] Security measures are in place

## 🎉 Success!

Your Laravel e-commerce project with Core Transaction 1, 2, and 3 data sharing capabilities is now successfully deployed and ready for production use!

### Next Steps:
1. **Test all functionality** thoroughly
2. **Set up monitoring** and alerting
3. **Configure backups** and disaster recovery
4. **Monitor performance** and optimize as needed
5. **Keep the system updated** with security patches

---

**Need Help?** Check the troubleshooting section or refer to the comprehensive documentation in the project files! 🚀
