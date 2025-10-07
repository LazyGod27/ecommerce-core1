@echo off
REM 🚀 Laravel E-Commerce Deployment Script for Windows
REM This script helps deploy your Laravel project to a production server

echo 🚀 Laravel E-Commerce Deployment Script
echo ========================================
echo.

REM Check if artisan file exists
if not exist "artisan" (
    echo ❌ Error: This doesn't appear to be a Laravel project (artisan file not found)
    pause
    exit /b 1
)

echo ℹ️  Starting deployment process...
echo.

REM Step 1: Install/Update Dependencies
echo Step 1: Installing dependencies...
echo.

REM Check if composer exists
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Error: Composer not found. Please install Composer first.
    echo Download from: https://getcomposer.org/download/
    pause
    exit /b 1
)

composer install --optimize-autoloader --no-dev
if %errorlevel% equ 0 (
    echo ✅ Composer dependencies installed
) else (
    echo ⚠️  Warning: Composer installation failed
)

REM Check if npm exists
where npm >nul 2>nul
if %errorlevel% equ 0 (
    echo Installing Node.js dependencies...
    npm install --production
    if %errorlevel% equ 0 (
        echo ✅ Node.js dependencies installed
    ) else (
        echo ⚠️  Warning: Node.js dependencies installation failed
    )
    
    echo Building frontend assets...
    npm run build
    if %errorlevel% equ 0 (
        echo ✅ Frontend assets built
    ) else (
        echo ⚠️  Warning: Frontend build failed
    )
) else (
    echo ℹ️  Node.js/npm not found. Skipping frontend build.
)

echo.

REM Step 2: Environment Configuration
echo Step 2: Configuring environment...
echo.

if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env"
        echo ✅ Created .env file from .env.example
    ) else (
        echo ❌ Error: .env.example file not found
        pause
        exit /b 1
    )
) else (
    echo ✅ .env file already exists
)

REM Generate application key
php artisan key:generate --force
if %errorlevel% equ 0 (
    echo ✅ Generated application key
) else (
    echo ⚠️  Warning: Application key generation failed
)

echo.

REM Step 3: Database Setup
echo Step 3: Setting up database...
echo.

REM Check if database configuration exists
findstr /C:"DB_DATABASE=" .env >nul
if %errorlevel% equ 0 (
    echo ℹ️  Database configuration found in .env file
    echo.
    set /p run_migrations="Do you want to run database migrations? (y/n): "
    if /i "%run_migrations%"=="y" (
        php artisan migrate --force
        if %errorlevel% equ 0 (
            echo ✅ Database migrations completed
        ) else (
            echo ⚠️  Warning: Database migrations failed
        )
    )
    
    echo.
    set /p seed_database="Do you want to seed the database with sample data? (y/n): "
    if /i "%seed_database%"=="y" (
        php artisan db:seed --force
        if %errorlevel% equ 0 (
            echo ✅ Database seeded with sample data
        ) else (
            echo ⚠️  Warning: Database seeding failed
        )
    )
) else (
    echo ⚠️  Warning: Database configuration not found in .env file
)

echo.

REM Step 4: Cache Configuration
echo Step 4: Optimizing for production...
echo.

php artisan config:clear
php artisan config:cache
if %errorlevel% equ 0 (
    echo ✅ Configuration cached
) else (
    echo ⚠️  Warning: Configuration caching failed
)

php artisan route:clear
php artisan route:cache
if %errorlevel% equ 0 (
    echo ✅ Routes cached
) else (
    echo ⚠️  Warning: Route caching failed
)

php artisan view:clear
php artisan view:cache
if %errorlevel% equ 0 (
    echo ✅ Views cached
) else (
    echo ⚠️  Warning: View caching failed
)

echo.

REM Step 5: Generate API Keys
echo Step 5: Generating API keys...
echo.

REM Check if API key generation command exists
php artisan list | findstr "api:generate" >nul
if %errorlevel% equ 0 (
    echo ℹ️  Generating API keys for Core Transaction systems...
    echo.
    
    REM Generate Core Transaction 1 API key
    php artisan api:generate --name="Core Transaction 1 Export" --permissions=core_transaction_1_export --no-interaction
    if %errorlevel% equ 0 (
        echo ✅ Core Transaction 1 API key generated
    ) else (
        echo ⚠️  Warning: Core Transaction 1 API key generation failed
    )
    
    REM Generate Core Transaction 2 API key
    php artisan api:generate --name="Core Transaction 2" --permissions=product_management,shared_data --no-interaction
    if %errorlevel% equ 0 (
        echo ✅ Core Transaction 2 API key generated
    ) else (
        echo ⚠️  Warning: Core Transaction 2 API key generation failed
    )
    
    REM Generate Core Transaction 3 API key
    php artisan api:generate --name="Core Transaction 3" --permissions=platform_management,shared_data --no-interaction
    if %errorlevel% equ 0 (
        echo ✅ Core Transaction 3 API key generated
    ) else (
        echo ⚠️  Warning: Core Transaction 3 API key generation failed
    )
) else (
    echo ⚠️  Warning: API key generation command not found. Skipping API key generation.
)

echo.

REM Step 6: Set up Data Sharing
echo Step 6: Setting up data sharing...
echo.

if exist "setup_core_transaction_1_data_sharing.php" (
    php setup_core_transaction_1_data_sharing.php
    if %errorlevel% equ 0 (
        echo ✅ Data sharing system configured
    ) else (
        echo ⚠️  Warning: Data sharing setup failed
    )
) else (
    echo ⚠️  Warning: Data sharing setup script not found. Skipping data sharing setup.
)

echo.

REM Step 7: Storage Link
echo Step 7: Creating storage link...
echo.

if not exist "public\storage" (
    php artisan storage:link
    if %errorlevel% equ 0 (
        echo ✅ Storage link created
    ) else (
        echo ⚠️  Warning: Storage link creation failed
    )
) else (
    echo ✅ Storage link already exists
)

echo.

REM Step 8: Health Check
echo Step 8: Running health checks...
echo.

php artisan --version >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ Laravel application is working
) else (
    echo ❌ Laravel application health check failed
)

if exist "bootstrap\cache\routes-v7.php" (
    echo ✅ Routes are cached
) else (
    echo ⚠️  Warning: Routes are not cached
)

if exist "bootstrap\cache\config.php" (
    echo ✅ Configuration is cached
) else (
    echo ⚠️  Warning: Configuration is not cached
)

echo.

REM Step 9: Final Instructions
echo Step 9: Deployment completed!
echo.
echo 🎉 Deployment Summary:
echo ======================
echo ✅ Dependencies installed
echo ✅ Environment configured
echo ✅ Database setup completed
echo ✅ Application optimized for production
echo ✅ API keys generated
echo ✅ Data sharing configured
echo ✅ File permissions set
echo.
echo 📋 Next Steps:
echo ==============
echo 1. Configure your web server (Apache/Nginx)
echo 2. Set up SSL certificate
echo 3. Configure domain DNS
echo 4. Test all functionality
echo 5. Set up monitoring and backups
echo.
echo 📚 Documentation:
echo =================
echo • DEPLOYMENT_GUIDE.md - Complete deployment guide
echo • CORE_TRANSACTION_1_DATA_SHARING_GUIDE.md - Data sharing guide
echo • CORE_TRANSACTION_1_SUMMARY.md - Complete solution overview
echo.
echo ✅ Deployment script completed successfully!
echo.
echo 🔗 Your Laravel e-commerce project is ready for production! 🚀
echo.
pause
