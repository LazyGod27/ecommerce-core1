<?php

/**
 * Core Transaction 2 Setup Script
 * 
 * This script helps set up the integration between Core Transaction 2
 * and the main e-commerce platform.
 */

require_once 'vendor/autoload.php';

use App\Models\ApiKey;
use App\Models\Seller;
use App\Models\Shop;

class CoreTransaction2Setup
{
    private $baseUrl;
    private $apiKey;
    private $apiSecret;

    public function __construct($baseUrl, $apiKey, $apiSecret)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * Test API connectivity
     */
    public function testConnection()
    {
        echo "Testing API connection...\n";
        
        $url = $this->baseUrl . '/external/products/1';
        $headers = [
            'X-API-Key: ' . $this->apiKey,
            'X-API-Secret: ' . $this->apiSecret
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 404) {
            echo "✅ API connection successful (HTTP $httpCode)\n";
            return true;
        } else {
            echo "❌ API connection failed (HTTP $httpCode)\n";
            return false;
        }
    }

    /**
     * Get available sellers
     */
    public function getSellers()
    {
        echo "Fetching available sellers...\n";
        
        $url = $this->baseUrl . '/public/sellers';
        $headers = [
            'X-API-Key: ' . $this->apiKey,
            'X-API-Secret: ' . $this->apiSecret
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            echo "✅ Found " . count($data['data']) . " sellers\n";
            return $data['data'];
        } else {
            echo "❌ Failed to fetch sellers (HTTP $httpCode)\n";
            return [];
        }
    }

    /**
     * Create a test product
     */
    public function createTestProduct($sellerId, $shopId)
    {
        echo "Creating test product...\n";
        
        $url = $this->baseUrl . '/external/products';
        $headers = [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey,
            'X-API-Secret: ' . $this->apiSecret
        ];

        $productData = [
            'seller_id' => $sellerId,
            'shop_id' => $shopId,
            'name' => 'Test Product from Core Transaction 2',
            'description' => 'This is a test product created by Core Transaction 2 integration',
            'price' => 99.99,
            'stock' => 10,
            'category' => 'Test',
            'image' => 'https://via.placeholder.com/300x300?text=Test+Product',
            'sku' => 'TEST-CT2-' . time(),
            'status' => 'active'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($productData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 201) {
            $data = json_decode($response, true);
            echo "✅ Test product created successfully (ID: " . $data['data']['id'] . ")\n";
            return $data['data'];
        } else {
            echo "❌ Failed to create test product (HTTP $httpCode)\n";
            echo "Response: $response\n";
            return null;
        }
    }

    /**
     * Update product stock
     */
    public function updateProductStock($productId, $newStock)
    {
        echo "Updating product stock...\n";
        
        $url = $this->baseUrl . "/external/products/$productId/stock";
        $headers = [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey,
            'X-API-Secret: ' . $this->apiSecret
        ];

        $stockData = [
            'stock' => $newStock,
            'operation' => 'set'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($stockData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            echo "✅ Product stock updated successfully (New stock: " . $data['data']['current_stock'] . ")\n";
            return true;
        } else {
            echo "❌ Failed to update product stock (HTTP $httpCode)\n";
            echo "Response: $response\n";
            return false;
        }
    }

    /**
     * Run full integration test
     */
    public function runIntegrationTest()
    {
        echo "🚀 Starting Core Transaction 2 Integration Test\n";
        echo "================================================\n\n";

        // Test 1: API Connection
        if (!$this->testConnection()) {
            echo "❌ Integration test failed at connection test\n";
            return false;
        }

        // Test 2: Get Sellers
        $sellers = $this->getSellers();
        if (empty($sellers)) {
            echo "❌ Integration test failed - no sellers found\n";
            return false;
        }

        // Test 3: Create Test Product
        $seller = $sellers[0];
        $product = $this->createTestProduct($seller['id'], $seller['shops'][0]['id'] ?? 1);
        if (!$product) {
            echo "❌ Integration test failed at product creation\n";
            return false;
        }

        // Test 4: Update Stock
        if (!$this->updateProductStock($product['id'], 25)) {
            echo "❌ Integration test failed at stock update\n";
            return false;
        }

        echo "\n🎉 Integration test completed successfully!\n";
        echo "✅ All Core Transaction 2 API endpoints are working correctly\n";
        
        return true;
    }
}

// Usage example
if (php_sapi_name() === 'cli') {
    echo "Core Transaction 2 Setup Script\n";
    echo "===============================\n\n";

    // Configuration
    $baseUrl = 'https://your-domain.com/api';
    $apiKey = 'your_api_key_here';
    $apiSecret = 'your_api_secret_here';

    // Create setup instance
    $setup = new CoreTransaction2Setup($baseUrl, $apiKey, $apiSecret);

    // Run integration test
    $setup->runIntegrationTest();
}











