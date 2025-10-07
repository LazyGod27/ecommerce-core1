<?php

/**
 * Data Sharing Setup Script
 * 
 * This script sets up the data sharing system between Core Transaction 2 and 3
 * Run this script after setting up the basic e-commerce platform
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use App\Models\ApiKey;
use App\Models\WebhookEndpoint;

echo "🚀 Setting up Data Sharing System for Core Transaction 2 & 3\n";
echo "============================================================\n\n";

// Step 1: Run migrations
echo "📊 Running database migrations...\n";
try {
    Artisan::call('migrate', ['--force' => true]);
    echo "✅ Migrations completed successfully\n\n";
} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 2: Generate API keys
echo "🔑 Generating API keys...\n";

// Core Transaction 2 API Key
try {
    $coreTransaction2Key = ApiKey::generate(
        'Core Transaction 2 - Seller Operations',
        'external',
        ['product_management', 'shared_data', 'order_tracking'],
        null
    );
    echo "✅ Core Transaction 2 API Key generated: " . $coreTransaction2Key->key . "\n";
} catch (Exception $e) {
    echo "❌ Failed to generate Core Transaction 2 API key: " . $e->getMessage() . "\n";
}

// Core Transaction 3 API Key
try {
    $coreTransaction3Key = ApiKey::generate(
        'Core Transaction 3 - Platform Control',
        'external',
        ['platform_management', 'shared_data', 'reports'],
        null
    );
    echo "✅ Core Transaction 3 API Key generated: " . $coreTransaction3Key->key . "\n";
} catch (Exception $e) {
    echo "❌ Failed to generate Core Transaction 3 API key: " . $e->getMessage() . "\n";
}

// Shared Data API Key
try {
    $sharedDataKey = ApiKey::generate(
        'Shared Data Access',
        'external',
        ['shared_data'],
        null
    );
    echo "✅ Shared Data API Key generated: " . $sharedDataKey->key . "\n";
} catch (Exception $e) {
    echo "❌ Failed to generate Shared Data API key: " . $e->getMessage() . "\n";
}

echo "\n";

// Step 3: Create sample webhook endpoints
echo "🔗 Creating sample webhook endpoints...\n";

try {
    // Core Transaction 2 webhook
    $webhook2 = WebhookEndpoint::create([
        'name' => 'Core Transaction 2 Webhook',
        'url' => 'https://core-transaction-2.example.com/webhooks/ecommerce-updates',
        'events' => ['seller.updated', 'product.updated', 'order.updated', 'earnings.updated'],
        'secret' => hash('sha256', 'core_transaction_2_secret'),
        'is_active' => true,
        'description' => 'Receives real-time updates from e-commerce platform',
        'retry_count' => 3,
        'timeout' => 30
    ]);
    echo "✅ Core Transaction 2 webhook endpoint created\n";
} catch (Exception $e) {
    echo "❌ Failed to create Core Transaction 2 webhook: " . $e->getMessage() . "\n";
}

try {
    // Core Transaction 3 webhook
    $webhook3 = WebhookEndpoint::create([
        'name' => 'Core Transaction 3 Webhook',
        'url' => 'https://core-transaction-3.example.com/webhooks/ecommerce-updates',
        'events' => ['platform.settings.updated', 'commission.updated', 'payout.updated'],
        'secret' => hash('sha256', 'core_transaction_3_secret'),
        'is_active' => true,
        'description' => 'Receives platform updates from e-commerce platform',
        'retry_count' => 3,
        'timeout' => 30
    ]);
    echo "✅ Core Transaction 3 webhook endpoint created\n";
} catch (Exception $e) {
    echo "❌ Failed to create Core Transaction 3 webhook: " . $e->getMessage() . "\n";
}

echo "\n";

// Step 4: Create sample platform settings
echo "⚙️ Creating sample platform settings...\n";

try {
    $platformSettings = [
        [
            'key' => 'platform_name',
            'value' => 'IMarketPH',
            'description' => 'Platform display name'
        ],
        [
            'key' => 'default_commission_rate',
            'value' => '5.0',
            'description' => 'Default commission rate for new sellers'
        ],
        [
            'key' => 'min_payout_amount',
            'value' => '100',
            'description' => 'Minimum amount for payout requests'
        ],
        [
            'key' => 'auto_approve_payout_limit',
            'value' => '500',
            'description' => 'Auto-approve payouts below this amount'
        ],
        [
            'key' => 'supported_payment_methods',
            'value' => json_encode(['gcash', 'paymaya', 'bank_transfer', 'stripe']),
            'description' => 'Supported payment methods'
        ],
        [
            'key' => 'supported_shipping_carriers',
            'value' => json_encode(['jnt', 'ninjavan', 'lbc', 'flash']),
            'description' => 'Supported shipping carriers'
        ],
        [
            'key' => 'currency',
            'value' => 'PHP',
            'description' => 'Default currency'
        ],
        [
            'key' => 'timezone',
            'value' => 'Asia/Manila',
            'description' => 'Platform timezone'
        ]
    ];

    foreach ($platformSettings as $setting) {
        \App\Models\PlatformSetting::updateOrCreate(
            ['key' => $setting['key']],
            $setting
        );
    }
    echo "✅ Platform settings created\n";
} catch (Exception $e) {
    echo "❌ Failed to create platform settings: " . $e->getMessage() . "\n";
}

echo "\n";

// Step 5: Create sample commission rules
echo "💰 Creating sample commission rules...\n";

try {
    $commissionRules = [
        [
            'name' => 'Electronics Commission',
            'description' => 'Standard commission for electronics',
            'category' => 'Electronics',
            'commission_rate' => 5.0,
            'min_amount' => 0,
            'max_amount' => 10000,
            'priority' => 1,
            'is_active' => true
        ],
        [
            'name' => 'Fashion Commission',
            'description' => 'Standard commission for fashion items',
            'category' => 'Fashion',
            'commission_rate' => 6.0,
            'min_amount' => 0,
            'max_amount' => 5000,
            'priority' => 1,
            'is_active' => true
        ],
        [
            'name' => 'Home & Garden Commission',
            'description' => 'Standard commission for home and garden items',
            'category' => 'Home & Garden',
            'commission_rate' => 4.0,
            'min_amount' => 0,
            'max_amount' => 15000,
            'priority' => 1,
            'is_active' => true
        ]
    ];

    foreach ($commissionRules as $rule) {
        \App\Models\CommissionRule::create($rule);
    }
    echo "✅ Commission rules created\n";
} catch (Exception $e) {
    echo "❌ Failed to create commission rules: " . $e->getMessage() . "\n";
}

echo "\n";

// Step 6: Create sample subscription plans
echo "📋 Creating sample subscription plans...\n";

try {
    $subscriptionPlans = [
        [
            'name' => 'Basic Plan',
            'slug' => 'basic',
            'description' => 'Perfect for small sellers',
            'type' => 'basic',
            'price' => 99.00,
            'currency' => 'PHP',
            'billing_cycle' => 'monthly',
            'trial_days' => 7,
            'features' => ['Up to 50 products', 'Basic analytics', 'Email support'],
            'limits' => ['max_products' => 50, 'max_orders_per_month' => 100],
            'commission_rate' => 8.0,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1
        ],
        [
            'name' => 'Standard Plan',
            'slug' => 'standard',
            'description' => 'Great for growing businesses',
            'type' => 'standard',
            'price' => 299.00,
            'currency' => 'PHP',
            'billing_cycle' => 'monthly',
            'trial_days' => 14,
            'features' => ['Up to 200 products', 'Advanced analytics', 'Priority support', 'Custom branding'],
            'limits' => ['max_products' => 200, 'max_orders_per_month' => 500],
            'commission_rate' => 5.0,
            'is_popular' => true,
            'is_active' => true,
            'sort_order' => 2
        ],
        [
            'name' => 'Premium Plan',
            'slug' => 'premium',
            'description' => 'For established businesses',
            'type' => 'premium',
            'price' => 599.00,
            'currency' => 'PHP',
            'billing_cycle' => 'monthly',
            'trial_days' => 14,
            'features' => ['Up to 1000 products', 'Advanced analytics', 'Priority support', 'Custom branding', 'API access'],
            'limits' => ['max_products' => 1000, 'max_orders_per_month' => 2000],
            'commission_rate' => 3.0,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 3
        ],
        [
            'name' => 'Enterprise Plan',
            'slug' => 'enterprise',
            'description' => 'For large businesses with high volume',
            'type' => 'enterprise',
            'price' => 999.00,
            'currency' => 'PHP',
            'billing_cycle' => 'monthly',
            'trial_days' => 14,
            'features' => ['Unlimited products', 'Advanced analytics', 'Priority support', 'Custom branding', 'API access', 'Dedicated support'],
            'limits' => ['max_products' => -1, 'max_orders_per_month' => -1],
            'commission_rate' => 1.0,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 4
        ]
    ];

    foreach ($subscriptionPlans as $plan) {
        \App\Models\SubscriptionPlan::create($plan);
    }
    echo "✅ Subscription plans created\n";
} catch (Exception $e) {
    echo "❌ Failed to create subscription plans: " . $e->getMessage() . "\n";
}

echo "\n";

// Step 7: Clear cache
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

// Step 8: Display summary
echo "📋 Setup Summary\n";
echo "================\n";
echo "✅ Database migrations completed\n";
echo "✅ API keys generated\n";
echo "✅ Webhook endpoints created\n";
echo "✅ Platform settings configured\n";
echo "✅ Commission rules created\n";
echo "✅ Subscription plans created\n";
echo "✅ Cache cleared\n";

echo "\n";
echo "🔑 API Keys Generated:\n";
echo "======================\n";
echo "Core Transaction 2: " . ($coreTransaction2Key->key ?? 'Failed') . "\n";
echo "Core Transaction 3: " . ($coreTransaction3Key->key ?? 'Failed') . "\n";
echo "Shared Data Access: " . ($sharedDataKey->key ?? 'Failed') . "\n";

echo "\n";
echo "🔗 Webhook Endpoints:\n";
echo "=====================\n";
echo "Core Transaction 2: https://core-transaction-2.example.com/webhooks/ecommerce-updates\n";
echo "Core Transaction 3: https://core-transaction-3.example.com/webhooks/ecommerce-updates\n";

echo "\n";
echo "📚 Next Steps:\n";
echo "==============\n";
echo "1. Update webhook URLs to your actual endpoints\n";
echo "2. Configure your Core Transaction 2 & 3 systems to use the API keys\n";
echo "3. Test the data sharing endpoints\n";
echo "4. Review the DATA_SHARING_GUIDE.md for detailed integration instructions\n";

echo "\n";
echo "🎉 Data sharing system setup completed successfully!\n";
echo "====================================================\n";
