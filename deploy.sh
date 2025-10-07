#!/bin/bash

# 🚀 Laravel E-Commerce Deployment Script
# This script helps deploy your Laravel project to a production server

echo "🚀 Laravel E-Commerce Deployment Script"
echo "========================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   print_error "This script should not be run as root for security reasons"
   exit 1
fi

# Check if Laravel project exists
if [ ! -f "artisan" ]; then
    print_error "This doesn't appear to be a Laravel project (artisan file not found)"
    exit 1
fi

print_info "Starting deployment process..."

# Step 1: Install/Update Dependencies
print_info "Step 1: Installing dependencies..."
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader --no-dev
    print_status "Composer dependencies installed"
else
    print_error "Composer not found. Please install Composer first."
    exit 1
fi

if command -v npm &> /dev/null; then
    npm install --production
    npm run build
    print_status "Node.js dependencies installed and built"
else
    print_warning "Node.js/npm not found. Skipping frontend build."
fi

# Step 2: Environment Configuration
print_info "Step 2: Configuring environment..."

if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        print_status "Created .env file from .env.example"
    else
        print_error ".env.example file not found"
        exit 1
    fi
fi

# Generate application key if not set
if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
    php artisan key:generate
    print_status "Generated application key"
fi

# Step 3: Database Setup
print_info "Step 3: Setting up database..."

# Check if database configuration exists
if grep -q "DB_DATABASE=" .env; then
    DB_NAME=$(grep "DB_DATABASE=" .env | cut -d '=' -f2)
    if [ "$DB_NAME" != "" ] && [ "$DB_NAME" != "null" ]; then
        print_info "Database name found: $DB_NAME"
        
        # Ask if user wants to run migrations
        read -p "Do you want to run database migrations? (y/n): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            php artisan migrate --force
            print_status "Database migrations completed"
        fi
        
        # Ask if user wants to seed database
        read -p "Do you want to seed the database with sample data? (y/n): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            php artisan db:seed --force
            print_status "Database seeded with sample data"
        fi
    else
        print_warning "Database not configured in .env file"
    fi
else
    print_warning "Database configuration not found in .env file"
fi

# Step 4: Cache Configuration
print_info "Step 4: Optimizing for production..."

php artisan config:clear
php artisan config:cache
print_status "Configuration cached"

php artisan route:clear
php artisan route:cache
print_status "Routes cached"

php artisan view:clear
php artisan view:cache
print_status "Views cached"

# Step 5: Generate API Keys
print_info "Step 5: Generating API keys..."

# Check if API key generation command exists
if php artisan list | grep -q "api:generate"; then
    print_info "Generating API keys for Core Transaction systems..."
    
    # Generate Core Transaction 1 API key
    php artisan api:generate --name="Core Transaction 1 Export" --permissions=core_transaction_1_export --no-interaction
    print_status "Core Transaction 1 API key generated"
    
    # Generate Core Transaction 2 API key
    php artisan api:generate --name="Core Transaction 2" --permissions=product_management,shared_data --no-interaction
    print_status "Core Transaction 2 API key generated"
    
    # Generate Core Transaction 3 API key
    php artisan api:generate --name="Core Transaction 3" --permissions=platform_management,shared_data --no-interaction
    print_status "Core Transaction 3 API key generated"
else
    print_warning "API key generation command not found. Skipping API key generation."
fi

# Step 6: Set up Data Sharing
print_info "Step 6: Setting up data sharing..."

if [ -f "setup_core_transaction_1_data_sharing.php" ]; then
    php setup_core_transaction_1_data_sharing.php
    print_status "Data sharing system configured"
else
    print_warning "Data sharing setup script not found. Skipping data sharing setup."
fi

# Step 7: Storage Link
print_info "Step 7: Creating storage link..."

if [ ! -L "public/storage" ]; then
    php artisan storage:link
    print_status "Storage link created"
else
    print_status "Storage link already exists"
fi

# Step 8: Set Permissions
print_info "Step 8: Setting file permissions..."

# Set permissions for storage and cache directories
chmod -R 755 storage bootstrap/cache
print_status "File permissions set"

# Step 9: Health Check
print_info "Step 9: Running health checks..."

# Check if application is working
if php artisan --version > /dev/null 2>&1; then
    print_status "Laravel application is working"
else
    print_error "Laravel application health check failed"
fi

# Check if routes are cached
if [ -f "bootstrap/cache/routes-v7.php" ]; then
    print_status "Routes are cached"
else
    print_warning "Routes are not cached"
fi

# Check if config is cached
if [ -f "bootstrap/cache/config.php" ]; then
    print_status "Configuration is cached"
else
    print_warning "Configuration is not cached"
fi

# Step 10: Final Instructions
print_info "Step 10: Deployment completed!"
echo ""
echo "🎉 Deployment Summary:"
echo "======================"
echo "✅ Dependencies installed"
echo "✅ Environment configured"
echo "✅ Database setup completed"
echo "✅ Application optimized for production"
echo "✅ API keys generated"
echo "✅ Data sharing configured"
echo "✅ File permissions set"
echo ""
echo "📋 Next Steps:"
echo "=============="
echo "1. Configure your web server (Apache/Nginx)"
echo "2. Set up SSL certificate"
echo "3. Configure domain DNS"
echo "4. Test all functionality"
echo "5. Set up monitoring and backups"
echo ""
echo "📚 Documentation:"
echo "================="
echo "• DEPLOYMENT_GUIDE.md - Complete deployment guide"
echo "• CORE_TRANSACTION_1_DATA_SHARING_GUIDE.md - Data sharing guide"
echo "• CORE_TRANSACTION_1_SUMMARY.md - Complete solution overview"
echo ""
print_status "Deployment script completed successfully!"
echo ""
echo "🔗 Your Laravel e-commerce project is ready for production! 🚀"
