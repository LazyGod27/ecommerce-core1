<?php

/**
 * Check Test User Password
 * 
 * This script checks the test user and resets password if needed
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "🔍 Checking Test User Password\n";
echo "==============================\n\n";

try {
    // Find the test user
    $user = User::where('email', 'test@example.com')->first();
    
    if (!$user) {
        echo "❌ Test user not found. Creating one...\n";
        
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);
        
        echo "✅ Test user created with password: password123\n";
    } else {
        echo "✅ Test user found: {$user->name} ({$user->email})\n";
        echo "   Created at: {$user->created_at}\n";
        echo "   Updated at: {$user->updated_at}\n";
    }
    
    // Reset password to a known value
    $user->password = Hash::make('password123');
    $user->save();
    
    echo "✅ Password reset to: password123\n\n";
    
    // Test password verification
    echo "🧪 Testing Password Verification:\n";
    echo "=================================\n";
    
    $testPasswords = ['password123', 'password', 'test123', 'admin'];
    
    foreach ($testPasswords as $testPassword) {
        $isValid = Hash::check($testPassword, $user->password);
        echo "Password '{$testPassword}': " . ($isValid ? '✅ Valid' : '❌ Invalid') . "\n";
    }
    
    echo "\n🎯 Login Information:\n";
    echo "====================\n";
    echo "Email: test@example.com\n";
    echo "Password: password123\n";
    echo "Name: {$user->name}\n\n";
    
    echo "🔗 Test Login URLs:\n";
    echo "==================\n";
    echo "Login Page: http://localhost/ecommerce-core1/login\n";
    echo "Orders Waiting: http://localhost/ecommerce-core1/orders-waiting-response\n";
    echo "Order Response Form: http://localhost/ecommerce-core1/orders/21/response\n\n";
    
    echo "✅ Test user is ready for testing!\n";
    
} catch (Exception $e) {
    echo "❌ Error checking test user: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
