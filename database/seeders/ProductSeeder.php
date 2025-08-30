<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Create sample products for testing
        $products = [
            [
                'name' => 'Gaming Laptop - RTX 4070',
                'description' => 'High-performance gaming laptop with NVIDIA RTX 4070 graphics, 16GB RAM, 1TB SSD, and 15.6" 144Hz display. Perfect for gaming and content creation.',
                'price' => 1299.99,
                'category' => 'Electronics',
                'image' => '/images/laptop-hot-deals.jpg',
                'stock' => 25,
                'rating' => 4.8,
                'reviews_count' => 12
            ],
            [
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'Premium wireless headphones with noise cancellation, 30-hour battery life, and crystal clear sound quality. Compatible with all devices.',
                'price' => 89.99,
                'category' => 'Electronics',
                'image' => '/images/default-product.jpg',
                'stock' => 50,
                'rating' => 4.6,
                'reviews_count' => 28
            ],
            [
                'name' => 'Smart Fitness Watch',
                'description' => 'Advanced fitness tracker with heart rate monitor, GPS, sleep tracking, and 7-day battery life. Water-resistant and compatible with iOS/Android.',
                'price' => 199.99,
                'category' => 'Electronics',
                'image' => '/images/default-product.jpg',
                'stock' => 35,
                'rating' => 4.7,
                'reviews_count' => 45
            ],
            [
                'name' => 'Classic Blue Denim Jeans',
                'description' => 'Premium quality denim jeans with perfect fit, comfortable stretch fabric, and classic blue wash. Available in multiple sizes.',
                'price' => 59.99,
                'category' => 'Clothing',
                'image' => '/images/shirt-hot-deals.jpg',
                'stock' => 100,
                'rating' => 4.5,
                'reviews_count' => 67
            ],
            [
                'name' => 'Casual Cotton T-Shirt',
                'description' => 'Soft, breathable cotton t-shirt with modern fit and comfortable design. Perfect for everyday wear and available in various colors.',
                'price' => 24.99,
                'category' => 'Clothing',
                'image' => '/images/shirt-hot-deals.jpg',
                'stock' => 150,
                'rating' => 4.4,
                'reviews_count' => 89
            ],
            [
                'name' => 'Running Shoes - Lightweight',
                'description' => 'Professional running shoes with lightweight design, cushioned sole, and breathable mesh upper. Ideal for long-distance running.',
                'price' => 129.99,
                'category' => 'Footwear',
                'image' => '/images/default-product.jpg',
                'stock' => 40,
                'rating' => 4.8,
                'reviews_count' => 34
            ],
            [
                'name' => 'Coffee Maker - Programmable',
                'description' => '12-cup programmable coffee maker with auto-shutoff, pause and serve feature, and easy-to-clean design. Perfect for home or office.',
                'price' => 79.99,
                'category' => 'Home & Kitchen',
                'image' => '/images/default-product.jpg',
                'stock' => 30,
                'rating' => 4.6,
                'reviews_count' => 23
            ],
            [
                'name' => 'Wireless Gaming Mouse',
                'description' => 'High-precision gaming mouse with 25K DPI sensor, programmable buttons, RGB lighting, and ultra-fast response time.',
                'price' => 69.99,
                'category' => 'Electronics',
                'image' => '/images/default-product.jpg',
                'stock' => 60,
                'rating' => 4.7,
                'reviews_count' => 41
            ],
            [
                'name' => 'Yoga Mat - Non-Slip',
                'description' => 'Premium yoga mat with non-slip surface, perfect thickness for comfort, and eco-friendly materials. Includes carrying strap.',
                'price' => 34.99,
                'category' => 'Sports & Fitness',
                'image' => '/images/default-product.jpg',
                'stock' => 80,
                'rating' => 4.5,
                'reviews_count' => 56
            ],
            [
                'name' => 'Portable Bluetooth Speaker',
                'description' => 'Waterproof portable speaker with 360-degree sound, 20-hour battery life, and built-in microphone for calls.',
                'price' => 49.99,
                'category' => 'Electronics',
                'image' => '/images/default-product.jpg',
                'stock' => 75,
                'rating' => 4.4,
                'reviews_count' => 38
            ]
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);
            
            // Create sample reviews for each product
            $this->createSampleReviews($product);
        }
    }

    private function createSampleReviews($product)
    {
        $reviews = [
            [
                'rating' => 5,
                'comment' => 'Excellent product! Exceeds all my expectations.',
                'user_name' => 'John D.'
            ],
            [
                'rating' => 4,
                'comment' => 'Great quality and fast delivery. Highly recommended!',
                'user_name' => 'Sarah M.'
            ],
            [
                'rating' => 5,
                'comment' => 'Perfect for my needs. Will definitely buy again.',
                'user_name' => 'Mike R.'
            ]
        ];

        foreach ($reviews as $reviewData) {
            Review::create([
                'product_id' => $product->id,
                'user_name' => $reviewData['user_name'],
                'rating' => $reviewData['rating'],
                'comment' => $reviewData['comment'],
                'created_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }
}
