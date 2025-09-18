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
        // Create sample users for reviews
        $users = User::factory(5)->create();
        
        // Create comprehensive products for all categories
        $products = [
            // GAMING CATEGORY
            [
                'name' => 'Gaming Laptop - RTX 4070',
                'description' => 'High-performance gaming laptop with NVIDIA RTX 4070 graphics, 16GB RAM, 1TB SSD, and 15.6" 144Hz display. Perfect for gaming and content creation.',
                'price' => 1299.99,
                'original_price' => 1499.99,
                'category' => 'gaming',
                'image' => 'https://placehold.co/400x400/4bc5ec/ffffff?text=Gaming+Laptop',
                'stock' => 25,
                'average_rating' => 4.8,
                'review_count' => 12
            ],
            [
                'name' => 'Gaming Mouse RGB',
                'description' => 'High-precision gaming mouse with 25K DPI sensor, programmable buttons, RGB lighting, and ultra-fast response time.',
                'price' => 69.99,
                'category' => 'gaming',
                'image' => 'https://placehold.co/400x400/94dcf4/353c61?text=Gaming+Mouse',
                'stock' => 60,
                'average_rating' => 4.7,
                'review_count' => 41
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB mechanical keyboard with Cherry MX switches, customizable lighting, and durable construction for gaming.',
                'price' => 129.99,
                'category' => 'gaming',
                'image' => 'https://placehold.co/400x400/5c6c9c/ffffff?text=Mechanical+Keyboard',
                'stock' => 45,
                'average_rating' => 4.6,
                'review_count' => 38
            ],
            [
                'name' => 'Gaming Headset',
                'description' => 'Premium gaming headset with 7.1 surround sound, noise cancellation, and comfortable memory foam ear cushions.',
                'price' => 89.99,
                'category' => 'gaming',
                'image' => 'https://placehold.co/400x400/bdccdc/353c61?text=Gaming+Headset',
                'stock' => 50,
                'average_rating' => 4.5,
                'review_count' => 28
            ],
            [
                'name' => 'Gaming Chair',
                'description' => 'Ergonomic gaming chair with lumbar support, adjustable height, and premium materials for long gaming sessions.',
                'price' => 299.99,
                'category' => 'gaming',
                'image' => 'https://placehold.co/400x400/4bc5ec/ffffff?text=Gaming+Chair',
                'stock' => 20,
                'average_rating' => 4.7,
                'review_count' => 15
            ],
            [
                'name' => 'Gaming Monitor',
                'description' => '27" 4K gaming monitor with 144Hz refresh rate, 1ms response time, and HDR support for immersive gaming.',
                'price' => 399.99,
                'category' => 'gaming',
                'image' => 'https://placehold.co/400x400/94dcf4/353c61?text=Gaming+Monitor',
                'stock' => 30,
                'average_rating' => 4.8,
                'review_count' => 22
            ],
            [
                'name' => 'Gaming Controller',
                'description' => 'Wireless gaming controller with precision analog sticks, customizable buttons, and long battery life.',
                'price' => 59.99,
                'category' => 'gaming',
                'image' => 'https://placehold.co/400x400/5c6c9c/ffffff?text=Gaming+Controller',
                'stock' => 40,
                'average_rating' => 4.4,
                'review_count' => 35
            ],
            [
                'name' => 'Gaming Mousepad',
                'description' => 'Large gaming mousepad with smooth surface, non-slip base, and RGB lighting for enhanced gaming experience.',
                'price' => 24.99,
                'category' => 'gaming',
                'image' => 'https://placehold.co/400x400/bdccdc/353c61?text=Gaming+Mousepad',
                'stock' => 80,
                'average_rating' => 4.3,
                'review_count' => 18
            ],

            // ACCESSORIES CATEGORY
            [
                'name' => 'Wireless Earbuds Pro',
                'description' => 'Premium wireless earbuds with active noise cancellation, 30-hour battery life, and crystal clear sound quality.',
                'price' => 149.99,
                'category' => 'accessories',
                'image' => 'https://placehold.co/400x400/4bc5ec/ffffff?text=Wireless+Earbuds',
                'stock' => 75,
                'average_rating' => 4.6,
                'review_count' => 42
            ],
            [
                'name' => 'Smart Watch Series 8',
                'description' => 'Advanced smartwatch with health monitoring, GPS, sleep tracking, and 7-day battery life. Water-resistant design.',
                'price' => 399.99,
                'category' => 'accessories',
                'image' => 'https://placehold.co/400x400/94dcf4/353c61?text=Smart+Watch',
                'stock' => 35,
                'average_rating' => 4.7,
                'review_count' => 45
            ],
            [
                'name' => 'Phone Case',
                'description' => 'Protective phone case with shock absorption, wireless charging compatibility, and stylish design.',
                'price' => 19.99,
                'category' => 'accessories',
                'image' => 'https://placehold.co/400x400/5c6c9c/ffffff?text=Phone+Case',
                'stock' => 100,
                'average_rating' => 4.4,
                'review_count' => 67
            ],
            [
                'name' => 'Power Bank 20000mAh',
                'description' => 'High-capacity power bank with fast charging, multiple ports, and LED indicator for remaining battery.',
                'price' => 39.99,
                'category' => 'accessories',
                'image' => 'https://placehold.co/400x400/bdccdc/353c61?text=Power+Bank',
                'stock' => 60,
                'average_rating' => 4.5,
                'review_count' => 38
            ],
            [
                'name' => 'Bluetooth Speaker',
                'description' => 'Waterproof portable speaker with 360-degree sound, 20-hour battery life, and built-in microphone.',
                'price' => 49.99,
                'category' => 'accessories',
                'image' => 'https://placehold.co/400x400/4bc5ec/ffffff?text=Bluetooth+Speaker',
                'stock' => 55,
                'average_rating' => 4.4,
                'review_count' => 29
            ],
            [
                'name' => 'USB Cable',
                'description' => 'Durable USB-C cable with fast charging capability, data transfer, and tangle-free design.',
                'price' => 12.99,
                'category' => 'accessories',
                'image' => 'https://placehold.co/400x400/94dcf4/353c61?text=USB+Cable',
                'stock' => 120,
                'average_rating' => 4.3,
                'review_count' => 25
            ],
            [
                'name' => 'Phone Stand',
                'description' => 'Adjustable phone stand with 360-degree rotation, stable base, and compatible with all phone sizes.',
                'price' => 15.99,
                'category' => 'accessories',
                'image' => 'https://placehold.co/400x400/5c6c9c/ffffff?text=Phone+Stand',
                'stock' => 90,
                'average_rating' => 4.2,
                'review_count' => 20
            ],
            [
                'name' => 'Car Charger',
                'description' => 'Dual-port car charger with fast charging technology, LED indicator, and universal compatibility.',
                'price' => 18.99,
                'category' => 'accessories',
                'image' => 'https://placehold.co/400x400/bdccdc/353c61?text=Car+Charger',
                'stock' => 70,
                'average_rating' => 4.4,
                'review_count' => 33
            ],

            // CLOTHING CATEGORY (jeans)
            [
                'name' => 'Classic Denim Jeans',
                'description' => 'Premium quality denim jeans with perfect fit, comfortable stretch fabric, and classic blue wash.',
                'price' => 59.99,
                'category' => 'jeans',
                'image' => 'https://placehold.co/400x400/4bc5ec/ffffff?text=Denim+Jeans',
                'stock' => 100,
                'average_rating' => 4.5,
                'review_count' => 67
            ],
            [
                'name' => 'Slim Fit Jeans',
                'description' => 'Modern slim fit jeans with stretch denim, tapered leg, and comfortable waistband for all-day wear.',
                'price' => 49.99,
                'category' => 'jeans',
                'image' => 'https://placehold.co/400x400/94dcf4/353c61?text=Slim+Fit+Jeans',
                'stock' => 80,
                'average_rating' => 4.4,
                'review_count' => 45
            ],
            [
                'name' => 'Skinny Jeans',
                'description' => 'Trendy skinny jeans with high stretch content, modern cut, and versatile styling options.',
                'price' => 54.99,
                'category' => 'jeans',
                'image' => 'https://placehold.co/400x400/5c6c9c/ffffff?text=Skinny+Jeans',
                'stock' => 75,
                'average_rating' => 4.3,
                'review_count' => 38
            ],
            [
                'name' => 'Bootcut Jeans',
                'description' => 'Classic bootcut jeans with relaxed fit through the thigh and slight flare at the hem.',
                'price' => 52.99,
                'category' => 'jeans',
                'image' => 'https://placehold.co/400x400/bdccdc/353c61?text=Bootcut+Jeans',
                'stock' => 65,
                'average_rating' => 4.4,
                'review_count' => 28
            ],
            [
                'name' => 'High Waist Jeans',
                'description' => 'Fashionable high waist jeans with flattering fit, stretch denim, and modern styling.',
                'price' => 57.99,
                'category' => 'jeans',
                'image' => 'https://placehold.co/400x400/4bc5ec/ffffff?text=High+Waist+Jeans',
                'stock' => 70,
                'average_rating' => 4.6,
                'review_count' => 41
            ],
            [
                'name' => 'Distressed Jeans',
                'description' => 'Stylish distressed jeans with authentic wear patterns, comfortable fit, and trendy appearance.',
                'price' => 64.99,
                'category' => 'jeans',
                'image' => 'https://placehold.co/400x400/94dcf4/353c61?text=Distressed+Jeans',
                'stock' => 55,
                'average_rating' => 4.5,
                'review_count' => 32
            ],
            [
                'name' => 'Black Jeans',
                'description' => 'Versatile black jeans with classic fit, comfortable stretch, and easy-care fabric.',
                'price' => 47.99,
                'category' => 'jeans',
                'image' => 'https://placehold.co/400x400/5c6c9c/ffffff?text=Black+Jeans',
                'stock' => 85,
                'average_rating' => 4.4,
                'review_count' => 36
            ],
            [
                'name' => 'Wide Leg Jeans',
                'description' => 'Comfortable wide leg jeans with relaxed fit, high-quality denim, and modern silhouette.',
                'price' => 61.99,
                'category' => 'jeans',
                'image' => 'https://placehold.co/400x400/bdccdc/353c61?text=Wide+Leg+Jeans',
                'stock' => 50,
                'average_rating' => 4.3,
                'review_count' => 24
            ],

            // BEAUTY CATEGORY (make-up)
            [
                'name' => 'Matte Lipstick Set',
                'description' => 'Premium matte lipstick set with long-lasting formula, vibrant colors, and smooth application.',
                'price' => 29.99,
                'category' => 'make-up',
                'image' => 'https://placehold.co/400x400/4bc5ec/ffffff?text=Lipstick+Set',
                'stock' => 60,
                'average_rating' => 4.6,
                'review_count' => 42
            ],
            [
                'name' => 'Foundation',
                'description' => 'Full coverage foundation with natural finish, SPF protection, and 24-hour wear.',
                'price' => 34.99,
                'category' => 'make-up',
                'image' => 'https://placehold.co/400x400/94dcf4/353c61?text=Foundation',
                'stock' => 45,
                'average_rating' => 4.5,
                'review_count' => 38
            ],
            [
                'name' => 'Mascara',
                'description' => 'Volumizing mascara with lengthening formula, waterproof finish, and easy removal.',
                'price' => 19.99,
                'category' => 'make-up',
                'image' => 'https://placehold.co/400x400/5c6c9c/ffffff?text=Mascara',
                'stock' => 80,
                'average_rating' => 4.4,
                'review_count' => 55
            ],
            [
                'name' => 'Eyeshadow Palette',
                'description' => 'Professional eyeshadow palette with 12 shades, blendable formula, and long-lasting wear.',
                'price' => 39.99,
                'category' => 'make-up',
                'image' => 'https://placehold.co/400x400/bdccdc/353c61?text=Eyeshadow+Palette',
                'stock' => 35,
                'average_rating' => 4.7,
                'review_count' => 28
            ],
            [
                'name' => 'Blush',
                'description' => 'Natural blush with buildable coverage, silky texture, and flattering finish for all skin tones.',
                'price' => 24.99,
                'category' => 'make-up',
                'image' => 'https://placehold.co/400x400/4bc5ec/ffffff?text=Blush',
                'stock' => 50,
                'average_rating' => 4.3,
                'review_count' => 31
            ],
            [
                'name' => 'Concealer',
                'description' => 'High-coverage concealer with creamy texture, easy blending, and natural finish.',
                'price' => 22.99,
                'category' => 'make-up',
                'image' => 'https://placehold.co/400x400/94dcf4/353c61?text=Concealer',
                'stock' => 65,
                'average_rating' => 4.5,
                'review_count' => 26
            ],
            [
                'name' => 'Setting Powder',
                'description' => 'Translucent setting powder with oil-absorbing properties and long-lasting finish.',
                'price' => 27.99,
                'category' => 'make-up',
                'image' => 'https://placehold.co/400x400/5c6c9c/ffffff?text=Setting+Powder',
                'stock' => 40,
                'average_rating' => 4.4,
                'review_count' => 22
            ],
            [
                'name' => 'Lip Gloss',
                'description' => 'Shiny lip gloss with non-sticky formula, moisturizing ingredients, and beautiful shine.',
                'price' => 16.99,
                'category' => 'make-up',
                'image' => 'https://placehold.co/400x400/bdccdc/353c61?text=Lip+Gloss',
                'stock' => 70,
                'average_rating' => 4.2,
                'review_count' => 19
            ]
        ];

        foreach ($products as $productData) {
            // Add original_price if not set (make it 20% higher than current price)
            if (!isset($productData['original_price'])) {
                $productData['original_price'] = $productData['price'] * 1.2;
            }
            
            $product = Product::create($productData);
            
            // Create sample reviews for each product
            $this->createSampleReviews($product, $users);
        }
    }

    private function createSampleReviews($product, $users)
    {
        $reviews = [
            [
                'rating' => 5,
                'comment' => 'Excellent product! Exceeds all my expectations.'
            ],
            [
                'rating' => 4,
                'comment' => 'Great quality and fast delivery. Highly recommended!'
            ],
            [
                'rating' => 5,
                'comment' => 'Perfect for my needs. Will definitely buy again.'
            ]
        ];

        foreach ($reviews as $reviewData) {
            Review::create([
                'product_id' => $product->id,
                'user_id' => $users->random()->id,
                'rating' => $reviewData['rating'],
                'comment' => $reviewData['comment'],
                'created_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }
}
