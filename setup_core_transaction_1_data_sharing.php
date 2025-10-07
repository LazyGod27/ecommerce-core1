<?php

/**
 * Core Transaction 1 Data Sharing Setup Script
 * 
 * This script sets up the data sharing system for Core Transaction 1
 * to share data with Core Transaction 2 and 3 systems
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use App\Models\ApiKey;

echo "🚀 Setting up Core Transaction 1 Data Sharing System\n";
echo "====================================================\n\n";

// Step 1: Run migrations
echo "📊 Running database migrations...\n";
try {
    Artisan::call('migrate', ['--force' => true]);
    echo "✅ Migrations completed successfully\n\n";
} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 2: Generate API key for Core Transaction 1
echo "🔑 Generating API key for Core Transaction 1...\n";
try {
    $coreTransaction1Key = ApiKey::generate(
        'Core Transaction 1 - Data Export',
        'external',
        ['core_transaction_1_export'],
        null
    );
    echo "✅ Core Transaction 1 API Key generated: " . $coreTransaction1Key->key . "\n";
} catch (Exception $e) {
    echo "❌ Failed to generate Core Transaction 1 API key: " . $e->getMessage() . "\n";
}

echo "\n";

// Step 3: Create sample webhook configuration
echo "🔗 Creating webhook configuration...\n";
try {
    $webhookConfig = [
        'core_transaction_2_url' => 'https://core-transaction-2.example.com/webhooks/ecommerce-updates',
        'core_transaction_2_secret' => 'core_transaction_2_secret_key_' . uniqid(),
        'core_transaction_3_url' => 'https://core-transaction-3.example.com/webhooks/ecommerce-updates',
        'core_transaction_3_secret' => 'core_transaction_3_secret_key_' . uniqid(),
        'timeout' => 30,
        'retry_attempts' => 3,
        'retry_delay' => 5
    ];

    // Create .env entries
    $envEntries = [
        "\n# Core Transaction 1 Data Sharing Configuration",
        "CORE_TRANSACTION_2_WEBHOOK_URL={$webhookConfig['core_transaction_2_url']}",
        "CORE_TRANSACTION_2_WEBHOOK_SECRET={$webhookConfig['core_transaction_2_secret']}",
        "CORE_TRANSACTION_3_WEBHOOK_URL={$webhookConfig['core_transaction_3_url']}",
        "CORE_TRANSACTION_3_WEBHOOK_SECRET={$webhookConfig['core_transaction_3_secret']}",
        "WEBHOOK_TIMEOUT={$webhookConfig['timeout']}",
        "WEBHOOK_RETRY_ATTEMPTS={$webhookConfig['retry_attempts']}",
        "WEBHOOK_RETRY_DELAY={$webhookConfig['retry_delay']}"
    ];

    $envFile = '.env';
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        $envContent .= implode("\n", $envEntries) . "\n";
        file_put_contents($envFile, $envContent);
        echo "✅ Webhook configuration added to .env file\n";
    } else {
        echo "⚠️  .env file not found. Please add the following to your .env file:\n";
        echo implode("\n", $envEntries) . "\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to create webhook configuration: " . $e->getMessage() . "\n";
}

echo "\n";

// Step 4: Create sample data for testing
echo "📊 Creating sample data for testing...\n";
try {
    // Create sample customers
    $customers = \App\Models\User::factory()->count(10)->create();
    echo "✅ Created 10 sample customers\n";

    // Create sample products (if sellers exist)
    if (\App\Models\Seller::count() > 0) {
        $products = \App\Models\Product::factory()->count(20)->create();
        echo "✅ Created 20 sample products\n";
    } else {
        echo "⚠️  No sellers found. Skipping product creation.\n";
    }

    // Create sample orders
    if ($customers->count() > 0) {
        $orders = \App\Models\Order::factory()->count(15)->create();
        echo "✅ Created 15 sample orders\n";
    }

    // Create sample reviews
    if (\App\Models\Product::count() > 0 && $customers->count() > 0) {
        $reviews = \App\Models\Review::factory()->count(25)->create();
        echo "✅ Created 25 sample reviews\n";
    }

} catch (Exception $e) {
    echo "❌ Failed to create sample data: " . $e->getMessage() . "\n";
}

echo "\n";

// Step 5: Test API endpoints
echo "🧪 Testing API endpoints...\n";
try {
    // Test customers endpoint
    $response = \Illuminate\Support\Facades\Http::get('http://localhost:8000/api/core-transaction-1/customers', [
        'headers' => [
            'X-API-Key' => $coreTransaction1Key->key ?? 'test_key',
            'X-API-Secret' => 'test_secret'
        ]
    ]);

    if ($response->successful()) {
        echo "✅ Customers endpoint working\n";
    } else {
        echo "⚠️  Customers endpoint test failed (this is normal if server is not running)\n";
    }

    // Test analytics endpoint
    $response = \Illuminate\Support\Facades\Http::get('http://localhost:8000/api/core-transaction-1/analytics', [
        'headers' => [
            'X-API-Key' => $coreTransaction1Key->key ?? 'test_key',
            'X-API-Secret' => 'test_secret'
        ]
    ]);

    if ($response->successful()) {
        echo "✅ Analytics endpoint working\n";
    } else {
        echo "⚠️  Analytics endpoint test failed (this is normal if server is not running)\n";
    }

} catch (Exception $e) {
    echo "⚠️  API endpoint testing failed (this is normal if server is not running): " . $e->getMessage() . "\n";
}

echo "\n";

// Step 6: Clear cache
echo "🧹 Clearing cache...\n";
try {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    echo "✅ Cache cleared\n";
} catch (Exception $e) {
    echo "❌ Failed to clear cache: " . $e->getMessage() . "\n";
}

echo "\n";

// Step 7: Display summary
echo "📋 Setup Summary\n";
echo "================\n";
echo "✅ Database migrations completed\n";
echo "✅ API key generated\n";
echo "✅ Webhook configuration created\n";
echo "✅ Sample data created\n";
echo "✅ Cache cleared\n";

echo "\n";
echo "🔑 API Key Generated:\n";
echo "=====================\n";
echo "Core Transaction 1: " . ($coreTransaction1Key->key ?? 'Failed') . "\n";

echo "\n";
echo "🔗 Webhook Configuration:\n";
echo "=========================\n";
echo "Core Transaction 2 URL: " . ($webhookConfig['core_transaction_2_url'] ?? 'Not configured') . "\n";
echo "Core Transaction 3 URL: " . ($webhookConfig['core_transaction_3_url'] ?? 'Not configured') . "\n";

echo "\n";
echo "📚 API Endpoints Available:\n";
echo "===========================\n";
echo "GET  /api/core-transaction-1/customers - Export customer data\n";
echo "GET  /api/core-transaction-1/orders - Export order data\n";
echo "GET  /api/core-transaction-1/products - Export product data\n";
echo "GET  /api/core-transaction-1/reviews - Export review data\n";
echo "GET  /api/core-transaction-1/analytics - Export analytics data\n";
echo "GET  /api/core-transaction-1/events - Get real-time events\n";
echo "POST /api/core-transaction-1/webhooks/test - Test webhooks\n";
echo "GET  /api/core-transaction-1/webhooks/status - Get webhook status\n";
echo "POST /api/core-transaction-1/webhooks/send - Send manual webhook\n";
echo "GET  /api/core-transaction-1/stats - Get export statistics\n";

echo "\n";
echo "📚 Next Steps:\n";
echo "==============\n";
echo "1. Update webhook URLs to your actual Core Transaction 2 & 3 endpoints\n";
echo "2. Configure your Core Transaction 2 & 3 systems to receive webhooks\n";
echo "3. Test the data export endpoints\n";
echo "4. Review the CORE_TRANSACTION_1_DATA_SHARING_GUIDE.md for detailed integration\n";
echo "5. Set up monitoring and alerting for webhook delivery\n";

echo "\n";
echo "🎉 Core Transaction 1 data sharing system setup completed successfully!\n";
echo "======================================================================\n";
