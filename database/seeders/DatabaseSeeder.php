<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Create sample products if they don't exist
        $products = [
            // Gaming Products
            ['name' => 'Gaming Mouse RGB', 'description' => 'High-performance gaming mouse with RGB lighting', 'price' => 1500.00, 'category' => 'gaming'],
            ['name' => 'Mechanical Keyboard', 'description' => 'Mechanical gaming keyboard with blue switches', 'price' => 2500.00, 'category' => 'gaming'],
            ['name' => 'Gaming Headset', 'description' => '7.1 surround sound gaming headset', 'price' => 1800.00, 'category' => 'gaming'],
            
            // Accessories
            ['name' => 'Wireless Earbuds', 'description' => 'Bluetooth wireless earbuds with noise cancellation', 'price' => 1200.00, 'category' => 'accessories'],
            ['name' => 'Smart Watch', 'description' => 'Fitness tracking smartwatch with heart rate monitor', 'price' => 3500.00, 'category' => 'accessories'],
            ['name' => 'Power Bank', 'description' => '20000mAh portable power bank', 'price' => 800.00, 'category' => 'accessories'],
            
            // Clothing
            ['name' => 'Classic Denim Jeans', 'description' => 'Comfortable classic fit denim jeans', 'price' => 1200.00, 'category' => 'clothing'],
            ['name' => 'Slim Fit Jeans', 'description' => 'Modern slim fit jeans for men', 'price' => 1100.00, 'category' => 'clothing'],
            ['name' => 'High Waist Jeans', 'description' => 'Fashionable high waist jeans for women', 'price' => 1300.00, 'category' => 'clothing'],
            
            // Beauty
            ['name' => 'Matte Lipstick', 'description' => 'Long-lasting matte lipstick in various shades', 'price' => 450.00, 'category' => 'beauty'],
            ['name' => 'Foundation', 'description' => 'Full coverage foundation for all skin types', 'price' => 650.00, 'category' => 'beauty'],
            ['name' => 'Mascara', 'description' => 'Volumizing mascara for dramatic lashes', 'price' => 350.00, 'category' => 'beauty'],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['name' => $productData['name']],
                [
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'original_price' => $productData['price'], // Set original price same as current price
                    'category' => $productData['category'],
                    'stock' => rand(10, 100),
                    'average_rating' => rand(35, 50) / 10,
                ]
            );
        }
    }
}
