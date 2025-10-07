<?php

/**
 * Upload to Shared Hosting Script
 * 
 * This script helps you prepare and upload your Laravel project to shared hosting
 * Run this script before uploading to your hosting provider
 */

echo "🚀 Laravel E-Commerce - Shared Hosting Upload Script\n";
echo "====================================================\n\n";

// Check if we're in a Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: This doesn't appear to be a Laravel project.\n";
    echo "Please run this script from your Laravel project root directory.\n";
    exit(1);
}

echo "📋 Pre-Upload Checklist\n";
echo "=======================\n\n";

// Step 1: Create production build
echo "1. Creating production build...\n";
if (file_exists('package.json')) {
    echo "   - Installing Node.js dependencies...\n";
    exec('npm install --production 2>&1', $output, $returnCode);
    if ($returnCode === 0) {
        echo "   ✅ Node.js dependencies installed\n";
    } else {
        echo "   ⚠️  Warning: Node.js dependencies installation failed\n";
    }
    
    echo "   - Building frontend assets...\n";
    exec('npm run build 2>&1', $output, $returnCode);
    if ($returnCode === 0) {
        echo "   ✅ Frontend assets built\n";
    } else {
        echo "   ⚠️  Warning: Frontend build failed\n";
    }
} else {
    echo "   ℹ️  No package.json found, skipping frontend build\n";
}

// Step 2: Optimize for production
echo "\n2. Optimizing for production...\n";
exec('php artisan config:cache 2>&1', $output, $returnCode);
if ($returnCode === 0) {
    echo "   ✅ Configuration cached\n";
} else {
    echo "   ⚠️  Warning: Configuration caching failed\n";
}

exec('php artisan route:cache 2>&1', $output, $returnCode);
if ($returnCode === 0) {
    echo "   ✅ Routes cached\n";
} else {
    echo "   ⚠️  Warning: Route caching failed\n";
}

exec('php artisan view:cache 2>&1', $output, $returnCode);
if ($returnCode === 0) {
    echo "   ✅ Views cached\n";
} else {
    echo "   ⚠️  Warning: View caching failed\n";
}

// Step 3: Create .env file if it doesn't exist
echo "\n3. Checking environment configuration...\n";
if (!file_exists('.env')) {
    if (file_exists('.env.example')) {
        copy('.env.example', '.env');
        echo "   ✅ Created .env file from .env.example\n";
    } else {
        echo "   ❌ Error: .env.example file not found\n";
        exit(1);
    }
} else {
    echo "   ✅ .env file already exists\n";
}

// Step 4: Generate application key
echo "\n4. Generating application key...\n";
exec('php artisan key:generate --force 2>&1', $output, $returnCode);
if ($returnCode === 0) {
    echo "   ✅ Application key generated\n";
} else {
    echo "   ⚠️  Warning: Application key generation failed\n";
}

// Step 5: Create upload package
echo "\n5. Creating upload package...\n";

// Files and directories to exclude
$excludeFiles = [
    'node_modules',
    '.git',
    '.gitignore',
    'tests',
    'phpunit.xml',
    'deploy.sh',
    'upload-to-hosting.php',
    'setup_*.php',
    '*.md',
    '.env',
    'storage/logs/*.log',
    'bootstrap/cache/*.php'
];

// Create zip file
$zipFile = 'ecommerce-core1-upload.zip';
$zip = new ZipArchive();

if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    echo "   ❌ Error: Cannot create zip file\n";
    exit(1);
}

// Function to add files to zip
function addFilesToZip($zip, $dir, $excludeFiles) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        $relativePath = substr($file->getRealPath(), strlen($dir) + 1);
        $shouldExclude = false;
        
        foreach ($excludeFiles as $pattern) {
            if (fnmatch($pattern, $relativePath) || fnmatch($pattern, basename($relativePath))) {
                $shouldExclude = true;
                break;
            }
        }
        
        if (!$shouldExclude) {
            $zip->addFile($file->getRealPath(), $relativePath);
        }
    }
}

// Add files to zip
addFilesToZip($zip, '.', $excludeFiles);
$zip->close();

echo "   ✅ Upload package created: $zipFile\n";

// Step 6: Create deployment instructions
echo "\n6. Creating deployment instructions...\n";

$instructions = "
# 🚀 Laravel E-Commerce - Shared Hosting Deployment Instructions

## 📦 Upload Package
- File: $zipFile
- Size: " . number_format(filesize($zipFile) / 1024 / 1024, 2) . " MB

## 📋 Upload Steps

### 1. Upload Files
1. Login to your hosting control panel (cPanel/WHM)
2. Open File Manager
3. Navigate to public_html (or your domain folder)
4. Upload the $zipFile file
5. Extract the zip file
6. Move all contents from the extracted folder to public_html

### 2. Set File Permissions
Set the following permissions:
- storage/ directory: 755 (or 775 if needed)
- bootstrap/cache/ directory: 755 (or 775 if needed)
- .env file: 644

### 3. Database Setup
1. Create a MySQL database in your hosting control panel
2. Create a database user with full privileges
3. Update the .env file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

### 4. Install Dependencies
If your hosting supports Composer:
```bash
composer install --optimize-autoloader --no-dev
```

### 5. Run Database Migrations
```bash
php artisan migrate --force
```

### 6. Generate API Keys
```bash
php artisan api:generate --name=\"Core Transaction 1 Export\" --permissions=core_transaction_1_export
php artisan api:generate --name=\"Core Transaction 2\" --permissions=product_management,shared_data
php artisan api:generate --name=\"Core Transaction 3\" --permissions=platform_management,shared_data
```

### 7. Set up Data Sharing
```bash
php setup_core_transaction_1_data_sharing.php
```

### 8. Create Storage Link
```bash
php artisan storage:link
```

## 🔧 Configuration

### Environment Variables
Update these in your .env file:
- APP_URL=https://yourdomain.com
- APP_ENV=production
- APP_DEBUG=false
- Database credentials
- Mail configuration
- Payment gateway credentials

### Webhook URLs
Configure these for data sharing:
- CORE_TRANSACTION_2_WEBHOOK_URL=https://core-transaction-2.yourdomain.com/webhooks/ecommerce-updates
- CORE_TRANSACTION_3_WEBHOOK_URL=https://core-transaction-3.yourdomain.com/webhooks/ecommerce-updates

## 🧪 Testing
1. Visit your domain to test the application
2. Test API endpoints: https://yourdomain.com/api/core-transaction-1/stats
3. Test webhook connectivity: https://yourdomain.com/api/core-transaction-1/webhooks/test

## 📚 Documentation
- DEPLOYMENT_GUIDE.md - Complete deployment guide
- CORE_TRANSACTION_1_DATA_SHARING_GUIDE.md - Data sharing guide
- CORE_TRANSACTION_1_SUMMARY.md - Complete solution overview

## 🆘 Support
If you encounter issues:
1. Check file permissions
2. Verify database connection
3. Check error logs in storage/logs/
4. Ensure all dependencies are installed
5. Verify .env configuration

---
Generated on: " . date('Y-m-d H:i:s') . "
";

file_put_contents('DEPLOYMENT_INSTRUCTIONS.txt', $instructions);
echo "   ✅ Deployment instructions created: DEPLOYMENT_INSTRUCTIONS.txt\n";

// Step 7: Final summary
echo "\n🎉 Upload Preparation Complete!\n";
echo "===============================\n\n";

echo "📦 Files Created:\n";
echo "• $zipFile - Upload package\n";
echo "• DEPLOYMENT_INSTRUCTIONS.txt - Step-by-step instructions\n\n";

echo "📋 Next Steps:\n";
echo "1. Upload $zipFile to your hosting provider\n";
echo "2. Follow the instructions in DEPLOYMENT_INSTRUCTIONS.txt\n";
echo "3. Configure your database and environment variables\n";
echo "4. Test your application\n\n";

echo "📚 Documentation:\n";
echo "• DEPLOYMENT_GUIDE.md - Complete deployment guide\n";
echo "• CORE_TRANSACTION_1_DATA_SHARING_GUIDE.md - Data sharing guide\n";
echo "• CORE_TRANSACTION_1_SUMMARY.md - Complete solution overview\n\n";

echo "🚀 Your Laravel e-commerce project is ready for upload! 🎉\n";
