<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;

class CategoryProductsSeeder extends Seeder
{
    public function run()
    {
        // Create sample users for reviews if they don't exist
        $users = User::count() > 0 ? User::all() : User::factory(10)->create();
        
        // Clear existing products to avoid duplicates
        // First clear related tables to avoid foreign key constraints
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Create comprehensive products for all categories
        $products = [
            // GAMING CATEGORY - Gaming Mice
            [
                'name' => 'Razer DeathAdder V3 Gaming Mouse',
                'description' => 'Professional gaming mouse with 30K DPI sensor, 8 programmable buttons, and ergonomic design for competitive gaming.',
                'price' => 2999.00,
                'original_price' => 3999.00,
                'category' => 'gaming',
                'image' => 'https://assets2.razerzone.com/images/pnx.assets/8a4e3b0b1b0b1b0b1b0b1b0b1b0b1b0b1b/razer-deathadder-v3-pro-black-1.png',
                'stock' => 25,
                'average_rating' => 4.8,
                'review_count' => 67
            ],
            [
                'name' => 'Logitech G Pro X Superlight Gaming Mouse',
                'description' => 'Ultra-lightweight gaming mouse with HERO sensor, 25K DPI, and wireless connectivity for professional esports.',
                'price' => 4599.00,
                'original_price' => 5999.00,
                'category' => 'gaming',
                'image' => 'https://resource.logitechg.com/w_692,c_lpad,ar_4:3,q_auto,f_auto,dpr_1.0/d_transparent.gif/content/dam/logitech/en/products/gaming/mice/pro-x-superlight/gallery/pro-x-superlight-gallery-1.png',
                'stock' => 30,
                'average_rating' => 4.9,
                'review_count' => 89
            ],
            [
                'name' => 'SteelSeries Rival 600 Gaming Mouse',
                'description' => 'Dual sensor gaming mouse with TrueMove3+ sensor, customizable weight system, and RGB lighting.',
                'price' => 3299.00,
                'original_price' => 4299.00,
                'category' => 'gaming',
                'image' => 'https://steelseries.com/cdn/shop/files/rival-600-gaming-mouse-black_1.png',
                'stock' => 20,
                'average_rating' => 4.7,
                'review_count' => 45
            ],
            [
                'name' => 'Corsair M65 RGB Elite Gaming Mouse',
                'description' => 'High-precision gaming mouse with 18K DPI sensor, aluminum frame, and customizable RGB lighting.',
                'price' => 2799.00,
                'original_price' => 3599.00,
                'category' => 'gaming',
                'image' => 'https://www.corsair.com/cdn/shop/products/CH-9309011-NA-M65-RGB-Elite-Gaming-Mouse_01.png',
                'stock' => 35,
                'average_rating' => 4.6,
                'review_count' => 52
            ],
            [
                'name' => 'HyperX Pulsefire Haste Gaming Mouse',
                'description' => 'Lightweight gaming mouse with TTC Golden micro switches, 16K DPI sensor, and honeycomb shell design.',
                'price' => 1999.00,
                'original_price' => 2599.00,
                'category' => 'gaming',
                'image' => 'https://www.hyperxgaming.com/cdn/shop/products/HX-MC001B_1.png',
                'stock' => 40,
                'average_rating' => 4.5,
                'review_count' => 38
            ],

            // GAMING CATEGORY - Gaming Keyboards
            [
                'name' => 'Razer BlackWidow V4 Pro Gaming Keyboard',
                'description' => 'Mechanical gaming keyboard with Razer Green switches, RGB lighting, and programmable macro keys.',
                'price' => 8999.00,
                'original_price' => 11999.00,
                'category' => 'gaming',
                'image' => 'https://assets2.razerzone.com/images/pnx.assets/8a4e3b0b1b0b1b0b1b0b1b0b1b0b1b0b1b/razer-blackwidow-v4-pro-black-1.png',
                'stock' => 15,
                'average_rating' => 4.8,
                'review_count' => 73
            ],
            [
                'name' => 'Logitech G915 TKL Wireless Gaming Keyboard',
                'description' => 'Wireless mechanical gaming keyboard with low-profile switches, RGB lighting, and 30-hour battery life.',
                'price' => 12999.00,
                'original_price' => 15999.00,
                'category' => 'gaming',
                'image' => 'https://resource.logitechg.com/w_692,c_lpad,ar_4:3,q_auto,f_auto,dpr_1.0/d_transparent.gif/content/dam/logitech/en/products/gaming/keyboards/g915-tkl-wireless-mechanical-gaming-keyboard/gallery/g915-tkl-wireless-mechanical-gaming-keyboard-gallery-1.png',
                'stock' => 12,
                'average_rating' => 4.9,
                'review_count' => 95
            ],
            [
                'name' => 'SteelSeries Apex Pro Gaming Keyboard',
                'description' => 'OmniPoint adjustable mechanical switches, OLED smart display, and customizable RGB lighting.',
                'price' => 9999.00,
                'original_price' => 12999.00,
                'category' => 'gaming',
                'image' => 'https://steelseries.com/cdn/shop/files/apex-pro-tkl-gaming-keyboard-black_1.png',
                'stock' => 18,
                'average_rating' => 4.7,
                'review_count' => 61
            ],
            [
                'name' => 'Corsair K100 RGB Gaming Keyboard',
                'description' => 'Mechanical gaming keyboard with Cherry MX Speed switches, 44-zone RGB lighting, and aluminum frame.',
                'price' => 10999.00,
                'original_price' => 13999.00,
                'category' => 'gaming',
                'image' => 'https://www.corsair.com/cdn/shop/products/CH-9119011-NA-K100-RGB-Mechanical-Gaming-Keyboard_01.png',
                'stock' => 10,
                'average_rating' => 4.8,
                'review_count' => 47
            ],
            [
                'name' => 'HyperX Alloy Elite 2 Gaming Keyboard',
                'description' => 'Mechanical gaming keyboard with HyperX Red switches, dedicated media keys, and RGB lighting.',
                'price' => 5999.00,
                'original_price' => 7999.00,
                'category' => 'gaming',
                'image' => 'https://www.hyperxgaming.com/cdn/shop/products/HX-KB7SL2-NA_1.png',
                'stock' => 25,
                'average_rating' => 4.6,
                'review_count' => 54
            ],
            [
                'name' => 'ASUS ROG Strix Scope II 96 Gaming Keyboard',
                'description' => 'Compact 96% gaming keyboard with ROG NX switches, hot-swappable design, and Aura Sync RGB.',
                'price' => 6999.00,
                'original_price' => 8999.00,
                'category' => 'gaming',
                'image' => 'https://rog.asus.com/cdn/shop/files/rog-strix-scope-ii-96-gaming-keyboard-black-1.png',
                'stock' => 22,
                'average_rating' => 4.5,
                'review_count' => 36
            ],

            // ELECTRONICS CATEGORY
            [
                'name' => 'KEELAT Cordless Electric Impact Wrench/Drill',
                'description' => 'Professional cordless electric impact wrench/drill with high torque and long battery life. Perfect for automotive and construction work.',
                'price' => 1389.00,
                'original_price' => 1922.00,
                'category' => 'electronics',
                'image' => 'https://down-my.img.susercontent.com/file/my-11134207-7r98q-lq4ig9w0mehhc5',
                'stock' => 25,
                'average_rating' => 4.6,
                'review_count' => 45
            ],
            [
                'name' => 'KEELAT Electric Wrench',
                'description' => 'High-performance electric wrench with variable speed control and ergonomic design for professional use.',
                'price' => 1389.00,
                'original_price' => 1922.00,
                'category' => 'electronics',
                'image' => 'https://down-id.img.susercontent.com/file/sg-11134201-7qvg2-lhyo2uv9l8ia6a',
                'stock' => 30,
                'average_rating' => 4.5,
                'review_count' => 38
            ],
            [
                'name' => 'Bluetooth Wireless Earbuds',
                'description' => 'High-quality wireless earbuds with noise cancellation and long battery life. Perfect for music and calls.',
                'price' => 438.00,
                'original_price' => 730.00,
                'category' => 'electronics',
                'image' => 'https://m.media-amazon.com/images/I/71Uo4RmAdHL._AC_.jpg',
                'stock' => 50,
                'average_rating' => 4.4,
                'review_count' => 67
            ],
            [
                'name' => 'Fitness Smartwatch',
                'description' => 'Advanced fitness smartwatch with heart rate monitoring, GPS, and water resistance. Track your health and fitness goals.',
                'price' => 1890.00,
                'original_price' => 2100.00,
                'category' => 'electronics',
                'image' => 'https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg',
                'stock' => 35,
                'average_rating' => 4.7,
                'review_count' => 52
            ],
            [
                'name' => 'Portable Power Bank',
                'description' => 'High-capacity portable power bank with fast charging and multiple device support. Keep your devices powered on the go.',
                'price' => 3955.00,
                'original_price' => 4653.00,
                'category' => 'electronics',
                'image' => 'https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg',
                'stock' => 40,
                'average_rating' => 4.5,
                'review_count' => 28
            ],
            [
                'name' => 'Classic Mini Game Console',
                'description' => 'Retro mini game console with built-in classic games. Perfect for nostalgic gaming and entertainment.',
                'price' => 349.00,
                'original_price' => 459.00,
                'category' => 'electronics',
                'image' => 'https://infinitebasics.com/cdn/shop/files/main_71e69d6a-e0b0-4af1-b272-c75b8353b0b2.jpg?v=1730917133',
                'stock' => 60,
                'average_rating' => 4.3,
                'review_count' => 41
            ],
            [
                'name' => 'Smart RGB Floor Lamp',
                'description' => 'Smart RGB floor lamp with app control, multiple colors, and adjustable brightness. Perfect for home ambiance.',
                'price' => 469.00,
                'original_price' => 1332.00,
                'category' => 'electronics',
                'image' => 'https://m.media-amazon.com/images/I/71p5fisYQCL._AC_SL1500_.jpg',
                'stock' => 25,
                'average_rating' => 4.6,
                'review_count' => 33
            ],
            [
                'name' => 'Wireless Gamepad Controller',
                'description' => 'Professional wireless gamepad controller with precision analog sticks and customizable buttons for gaming.',
                'price' => 632.00,
                'original_price' => 973.00,
                'category' => 'electronics',
                'image' => 'https://i5.walmartimages.com/asr/b30bd280-e303-4d72-802e-1098752e3741_1.f164d3532b3ba852a89e92972f4d4754.jpeg',
                'stock' => 45,
                'average_rating' => 4.4,
                'review_count' => 56
            ],
            [
                'name' => 'Logitech Wireless Gaming Headset',
                'description' => 'Premium wireless gaming headset with 7.1 surround sound, noise cancellation, and comfortable design.',
                'price' => 5000.00,
                'original_price' => 5882.00,
                'category' => 'electronics',
                'image' => 'https://resource.astrogaming.com/d_transparent.gif/content/dam/astro/en/products/a30/pdp-gallery-a30-white-01.png',
                'stock' => 20,
                'average_rating' => 4.8,
                'review_count' => 29
            ],
            [
                'name' => 'Fast Wireless Charging Pad',
                'description' => 'Fast wireless charging pad with LED indicator and universal compatibility for smartphones and devices.',
                'price' => 850.00,
                'original_price' => 1000.00,
                'category' => 'electronics',
                'image' => 'https://i5.walmartimages.com/asr/d493dbcf-87a9-49d6-a3d9-ce24b976e839.decbcde8849150199b8f1e0d47787236.jpeg',
                'stock' => 35,
                'average_rating' => 4.5,
                'review_count' => 42
            ],
            [
                'name' => 'Smart Speaker with Voice Assistant',
                'description' => 'Smart speaker with built-in voice assistant, high-quality audio, and smart home integration.',
                'price' => 1999.00,
                'original_price' => 2500.00,
                'category' => 'electronics',
                'image' => 'https://img.freepik.com/premium-photo/smart-speaker-with-voice-assistant_506134-10019.jpg',
                'stock' => 30,
                'average_rating' => 4.6,
                'review_count' => 38
            ],
            [
                'name' => 'Waterproof Portable Bluetooth Speaker',
                'description' => 'Waterproof portable Bluetooth speaker with 360-degree sound and long battery life for outdoor adventures.',
                'price' => 750.00,
                'original_price' => 1000.00,
                'category' => 'electronics',
                'image' => 'https://i5.walmartimages.com/seo/Bluetooth-Speaker-HD-Sound-Portable-Wireless-IPX5-Waterproof-Up-24H-Playtime-TWS-Pairing-BT5-3-Home-Party-Outdoor-Beach-Electronic-Gadgets-Birthday-G_6b79ed12-e892-459c-a917-b00eb54c8c20.c08273c52c6820892dfc55dfdcd9ca0e.jpeg',
                'stock' => 50,
                'average_rating' => 4.4,
                'review_count' => 61
            ],
            [
                'name' => '1TB Portable External Hard Drive',
                'description' => 'High-speed 1TB portable external hard drive with USB 3.0 connectivity and compact design.',
                'price' => 2800.00,
                'original_price' => 3111.00,
                'category' => 'electronics',
                'image' => 'https://www.royalcomputersolution.com/wp-content/uploads/2019/06/lenovo-extrnal-2tb-hard-drive-3-1024x984.jpg',
                'stock' => 25,
                'average_rating' => 4.5,
                'review_count' => 34
            ],
            [
                'name' => '128GB High-Speed USB Flash Drive',
                'description' => 'High-speed 128GB USB flash drive with fast data transfer and durable construction.',
                'price' => 550.00,
                'original_price' => 786.00,
                'category' => 'electronics',
                'image' => 'https://images-na.ssl-images-amazon.com/images/I/61wk1z9PrZL.jpg',
                'stock' => 80,
                'average_rating' => 4.3,
                'review_count' => 47
            ],
            [
                'name' => '4K Camera Drone with GPS',
                'description' => 'Professional 4K camera drone with GPS positioning, intelligent flight modes, and stable aerial photography.',
                'price' => 12000.00,
                'original_price' => 14118.00,
                'category' => 'electronics',
                'image' => 'https://m.media-amazon.com/images/I/718Btp+-iCL._AC_.jpg',
                'stock' => 15,
                'average_rating' => 4.7,
                'review_count' => 18
            ],
            [
                'name' => 'GoPro HERO12 Action Camera',
                'description' => 'Professional action camera with 4K video recording, waterproof design, and advanced stabilization.',
                'price' => 18500.00,
                'original_price' => 21023.00,
                'category' => 'electronics',
                'image' => 'https://microless.com/cdn/products/6a05af6ac59c50a51c41219ac3dbc57c-hi.jpg',
                'stock' => 12,
                'average_rating' => 4.8,
                'review_count' => 22
            ],
            [
                'name' => 'Smart Power Strip with USB Ports',
                'description' => 'Smart power strip with USB charging ports, surge protection, and energy monitoring capabilities.',
                'price' => 890.00,
                'original_price' => 967.00,
                'category' => 'electronics',
                'image' => 'https://m.media-amazon.com/images/I/61MScwtXX2L._AC_SL1500_.jpg',
                'stock' => 40,
                'average_rating' => 4.4,
                'review_count' => 35
            ],
            [
                'name' => 'Digital Smart Scale',
                'description' => 'Smart digital scale with body composition analysis, Bluetooth connectivity, and health tracking features.',
                'price' => 999.00,
                'original_price' => 1281.00,
                'category' => 'electronics',
                'image' => 'https://m.media-amazon.com/images/I/61eFoh9Kg9L._AC_SL1500_.jpg',
                'stock' => 30,
                'average_rating' => 4.5,
                'review_count' => 28
            ],
            [
                'name' => '1080P Car Dash Cam',
                'description' => 'High-definition car dash cam with night vision, loop recording, and emergency recording features.',
                'price' => 1750.00,
                'original_price' => 1944.00,
                'category' => 'electronics',
                'image' => 'https://i5.walmartimages.com/asr/e21a1dff-559d-4ef0-a06a-764c1b7cd4ed_1.b0b46d7905d569fb68cbfa8862099d69.jpeg',
                'stock' => 25,
                'average_rating' => 4.6,
                'review_count' => 31
            ],

            // FASHION CATEGORY
            [
                'name' => 'Vintage Low-Top Sneakers',
                'description' => 'Classic vintage low-top sneakers with premium materials and timeless design. Perfect for casual wear.',
                'price' => 8100.00,
                'original_price' => 18000.00,
                'category' => 'fashion',
                'image' => 'https://media-assets.grailed.com/prd/listing/47294752/da782adc9b964af09f259b6546d20c0a?h=700&fit=clip&auto=format',
                'stock' => 50,
                'average_rating' => 4.7,
                'review_count' => 89
            ],
            [
                'name' => 'Oversized Streetwear Joggers',
                'description' => 'Comfortable oversized streetwear joggers perfect for casual wear. Made with premium materials.',
                'price' => 300.00,
                'original_price' => 350.00,
                'category' => 'fashion',
                'image' => 'https://media-assets.grailed.com/prd/listing/temp/d8d385ab19bf4f9b891e6326fa393321?',
                'stock' => 80,
                'average_rating' => 4.5,
                'review_count' => 67
            ],
            [
                'name' => 'Minimalist Everyday Backpack',
                'description' => 'Korean aesthetic shoulder bag with minimalist design and practical functionality for daily use.',
                'price' => 750.00,
                'original_price' => 1500.00,
                'category' => 'fashion',
                'image' => 'https://www.uoozee.com/cdn/shop/files/751ae0bcc7281673f383d0d20578e0d1.jpg?v=1748435092&width=1000',
                'stock' => 45,
                'average_rating' => 4.6,
                'review_count' => 52
            ],
            [
                'name' => 'Oversized Vibe Check Hoodie',
                'description' => 'F1 Racing oversize hoodie with comfortable fit and trendy design. Perfect for streetwear style.',
                'price' => 750.00,
                'original_price' => 1500.00,
                'category' => 'fashion',
                'image' => 'https://i.pinimg.com/1200x/7c/5c/51/7c5c51e042c735453478a3328643c6f5.jpg',
                'stock' => 60,
                'average_rating' => 4.4,
                'review_count' => 73
            ],
            [
                'name' => 'Light-Wash Unisex Denim Jacket',
                'description' => 'Classic light-wash unisex denim jacket with timeless design and comfortable fit.',
                'price' => 1399.00,
                'original_price' => 2798.00,
                'category' => 'fashion',
                'image' => 'https://i.pinimg.com/736x/f7/e7/e7/f7e7e739c2b6012c731d1dd0e4b31d95.jpg',
                'stock' => 35,
                'average_rating' => 4.5,
                'review_count' => 41
            ],
            [
                'name' => 'Ribbed Knit Cropped Top',
                'description' => 'Stylish ribbed knit cropped top with comfortable fit and modern design.',
                'price' => 350.00,
                'original_price' => 450.00,
                'category' => 'fashion',
                'image' => 'https://i.pinimg.com/1200x/48/e9/32/48e932a07da8b48ac272a24b06051be7.jpg',
                'stock' => 70,
                'average_rating' => 4.3,
                'review_count' => 58
            ],
            [
                'name' => 'Adjustable Strapback Cap',
                'description' => 'New York Yankees adjustable strapback cap with classic design and comfortable fit.',
                'price' => 995.00,
                'original_price' => 1195.00,
                'category' => 'fashion',
                'image' => 'https://i.pinimg.com/1200x/d6/33/3a/d6333a2fedc7e198dcbd9c607f5a3f31.jpg',
                'stock' => 55,
                'average_rating' => 4.4,
                'review_count' => 36
            ],
            [
                'name' => 'Unisex Crewneck Sweater',
                'description' => 'Standard sweatshirt with comfortable fit and versatile design for everyday wear.',
                'price' => 1072.00,
                'original_price' => 1599.00,
                'category' => 'fashion',
                'image' => 'https://i.pinimg.com/736x/7b/52/3e/7b523ed6e065e78fdf998d07efb747da.jpg',
                'stock' => 65,
                'average_rating' => 4.5,
                'review_count' => 49
            ],
            [
                'name' => 'Unisex Graphic T-Shirt',
                'description' => 'Men cotton plant & slogan tee with graphic design and comfortable cotton material.',
                'price' => 1499.00,
                'original_price' => 3299.00,
                'category' => 'fashion',
                'image' => 'https://i.pinimg.com/1200x/d9/4f/7b/d94f7b2222d32a52842369633787ef2d.jpg',
                'stock' => 90,
                'average_rating' => 4.6,
                'review_count' => 82
            ],

            // BEAUTY CATEGORY
            [
                'name' => 'Beauty Kit',
                'description' => 'Complete beauty kit with essential skincare products for daily beauty routine.',
                'price' => 299.00,
                'original_price' => 399.00,
                'category' => 'beauty',
                'image' => 'ssa/soap.jpg',
                'stock' => 40,
                'average_rating' => 4.5,
                'review_count' => 32
            ],

            // SPORTS CATEGORY
            [
                'name' => 'Professional Basketball',
                'description' => 'Official size professional basketball with premium leather and excellent grip for indoor and outdoor play.',
                'price' => 899.00,
                'original_price' => 1299.00,
                'category' => 'sports',
                'image' => 'https://m.media-amazon.com/images/I/71Uo4RmAdHL._AC_.jpg',
                'stock' => 30,
                'average_rating' => 4.6,
                'review_count' => 45
            ],
            [
                'name' => 'Yoga Mat Premium',
                'description' => 'High-quality yoga mat with non-slip surface and comfortable cushioning for all yoga practices.',
                'price' => 1299.00,
                'original_price' => 1899.00,
                'category' => 'sports',
                'image' => 'https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg',
                'stock' => 50,
                'average_rating' => 4.7,
                'review_count' => 38
            ],
            [
                'name' => 'Running Shoes',
                'description' => 'Professional running shoes with advanced cushioning and breathable design for optimal performance.',
                'price' => 2499.00,
                'original_price' => 3299.00,
                'category' => 'sports',
                'image' => 'https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg',
                'stock' => 25,
                'average_rating' => 4.8,
                'review_count' => 52
            ],

            // TOYS CATEGORY
            [
                'name' => 'Gaming Controller',
                'description' => 'Professional gaming controller for PS5 with customizable buttons and triggers for enhanced gaming experience.',
                'price' => 7999.00,
                'original_price' => 9999.00,
                'category' => 'toys',
                'image' => 'ssa/controller.jpg',
                'stock' => 35,
                'average_rating' => 4.7,
                'review_count' => 28
            ],

            // GROCERIES CATEGORY
            [
                'name' => 'Insulated Water Bottle',
                'description' => 'High-quality insulated water bottle that keeps drinks cold for 24 hours and hot for 12 hours.',
                'price' => 399.00,
                'original_price' => 599.00,
                'category' => 'groceries',
                'image' => 'ssa/water.jpg',
                'stock' => 60,
                'average_rating' => 4.5,
                'review_count' => 41
            ],

            // HOME CATEGORY
            [
                'name' => 'Smart Home Hub',
                'description' => 'Central smart home hub that controls all your smart devices with voice commands and mobile app.',
                'price' => 1999.00,
                'original_price' => 2499.00,
                'category' => 'home',
                'image' => 'https://m.media-amazon.com/images/I/71p5fisYQCL._AC_SL1500_.jpg',
                'stock' => 20,
                'average_rating' => 4.6,
                'review_count' => 33
            ],
            [
                'name' => 'LED Desk Lamp',
                'description' => 'Adjustable LED desk lamp with multiple brightness levels and USB charging port.',
                'price' => 599.00,
                'original_price' => 799.00,
                'category' => 'home',
                'image' => 'https://m.media-amazon.com/images/I/61x5sP-mFDL._AC_SL1500_.jpg',
                'stock' => 45,
                'average_rating' => 4.4,
                'review_count' => 26
            ],

            // BEST SELLING CATEGORY (mix of popular items)
            [
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'Premium wireless Bluetooth headphones with noise cancellation and 30-hour battery life.',
                'price' => 1299.00,
                'original_price' => 1799.00,
                'category' => 'best',
                'image' => 'https://m.media-amazon.com/images/I/71Uo4RmAdHL._AC_.jpg',
                'stock' => 100,
                'average_rating' => 4.8,
                'review_count' => 156
            ],
            [
                'name' => 'Smartphone Case',
                'description' => 'Protective smartphone case with shock absorption and wireless charging compatibility.',
                'price' => 299.00,
                'original_price' => 399.00,
                'category' => 'best',
                'image' => 'https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg',
                'stock' => 200,
                'average_rating' => 4.6,
                'review_count' => 234
            ],
            [
                'name' => 'Portable Phone Charger',
                'description' => 'High-capacity portable phone charger with fast charging technology and LED indicator.',
                'price' => 599.00,
                'original_price' => 799.00,
                'category' => 'best',
                'image' => 'https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg',
                'stock' => 150,
                'average_rating' => 4.7,
                'review_count' => 189
            ],

            // NEW ARRIVALS CATEGORY
            [
                'name' => 'Latest Smartphone Model',
                'description' => 'Brand new smartphone with advanced features, high-resolution camera, and long battery life.',
                'price' => 15999.00,
                'original_price' => 19999.00,
                'category' => 'new',
                'image' => 'https://m.media-amazon.com/images/I/71p5fisYQCL._AC_SL1500_.jpg',
                'stock' => 15,
                'average_rating' => 4.9,
                'review_count' => 12
            ],
            [
                'name' => 'AI-Powered Smart Speaker',
                'description' => 'Next-generation AI-powered smart speaker with advanced voice recognition and smart home integration.',
                'price' => 2999.00,
                'original_price' => 3999.00,
                'category' => 'new',
                'image' => 'https://m.media-amazon.com/images/I/61x5sP-mFDL._AC_SL1500_.jpg',
                'stock' => 25,
                'average_rating' => 4.8,
                'review_count' => 8
            ]
        ];

        foreach ($products as $productData) {
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
                'comment' => 'Excellent product! Exceeds all my expectations. Highly recommended!'
            ],
            [
                'rating' => 4,
                'comment' => 'Great quality and fast delivery. Very satisfied with my purchase.'
            ],
            [
                'rating' => 5,
                'comment' => 'Perfect for my needs. Will definitely buy again from this store.'
            ],
            [
                'rating' => 4,
                'comment' => 'Good product with reasonable price. Delivery was quick and packaging was excellent.'
            ],
            [
                'rating' => 5,
                'comment' => 'Amazing quality! This is exactly what I was looking for. Thank you!'
            ]
        ];

        // Create 2-4 random reviews for each product
        $numReviews = rand(2, 4);
        $selectedReviews = array_slice($reviews, 0, $numReviews);

        foreach ($selectedReviews as $reviewData) {
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

                'price' => 750.00,

                'original_price' => 1500.00,

                'category' => 'fashion',

                'image' => 'https://i.pinimg.com/1200x/7c/5c/51/7c5c51e042c735453478a3328643c6f5.jpg',

                'stock' => 60,

                'average_rating' => 4.4,

                'review_count' => 73

            ],

            [

                'name' => 'Light-Wash Unisex Denim Jacket',

                'description' => 'Classic light-wash unisex denim jacket with timeless design and comfortable fit.',

                'price' => 1399.00,

                'original_price' => 2798.00,

                'category' => 'fashion',

                'image' => 'https://i.pinimg.com/736x/f7/e7/e7/f7e7e739c2b6012c731d1dd0e4b31d95.jpg',

                'stock' => 35,

                'average_rating' => 4.5,

                'review_count' => 41

            ],

            [

                'name' => 'Ribbed Knit Cropped Top',

                'description' => 'Stylish ribbed knit cropped top with comfortable fit and modern design.',

                'price' => 350.00,

                'original_price' => 450.00,

                'category' => 'fashion',

                'image' => 'https://i.pinimg.com/1200x/48/e9/32/48e932a07da8b48ac272a24b06051be7.jpg',

                'stock' => 70,

                'average_rating' => 4.3,

                'review_count' => 58

            ],

            [

                'name' => 'Adjustable Strapback Cap',

                'description' => 'New York Yankees adjustable strapback cap with classic design and comfortable fit.',

                'price' => 995.00,

                'original_price' => 1195.00,

                'category' => 'fashion',

                'image' => 'https://i.pinimg.com/1200x/d6/33/3a/d6333a2fedc7e198dcbd9c607f5a3f31.jpg',

                'stock' => 55,

                'average_rating' => 4.4,

                'review_count' => 36

            ],

            [

                'name' => 'Unisex Crewneck Sweater',

                'description' => 'Standard sweatshirt with comfortable fit and versatile design for everyday wear.',

                'price' => 1072.00,

                'original_price' => 1599.00,

                'category' => 'fashion',

                'image' => 'https://i.pinimg.com/736x/7b/52/3e/7b523ed6e065e78fdf998d07efb747da.jpg',

                'stock' => 65,

                'average_rating' => 4.5,

                'review_count' => 49

            ],

            [

                'name' => 'Unisex Graphic T-Shirt',

                'description' => 'Men cotton plant & slogan tee with graphic design and comfortable cotton material.',

                'price' => 1499.00,

                'original_price' => 3299.00,

                'category' => 'fashion',

                'image' => 'https://i.pinimg.com/1200x/d9/4f/7b/d94f7b2222d32a52842369633787ef2d.jpg',

                'stock' => 90,

                'average_rating' => 4.6,

                'review_count' => 82

            ],



            // BEAUTY CATEGORY

            [

                'name' => 'Beauty Kit',

                'description' => 'Complete beauty kit with essential skincare products for daily beauty routine.',

                'price' => 299.00,

                'original_price' => 399.00,

                'category' => 'beauty',

                'image' => 'ssa/soap.jpg',

                'stock' => 40,

                'average_rating' => 4.5,

                'review_count' => 32

            ],



            // SPORTS CATEGORY

            [

                'name' => 'Professional Basketball',

                'description' => 'Official size professional basketball with premium leather and excellent grip for indoor and outdoor play.',

                'price' => 899.00,

                'original_price' => 1299.00,

                'category' => 'sports',

                'image' => 'https://m.media-amazon.com/images/I/71Uo4RmAdHL._AC_.jpg',

                'stock' => 30,

                'average_rating' => 4.6,

                'review_count' => 45

            ],

            [

                'name' => 'Yoga Mat Premium',

                'description' => 'High-quality yoga mat with non-slip surface and comfortable cushioning for all yoga practices.',

                'price' => 1299.00,

                'original_price' => 1899.00,

                'category' => 'sports',

                'image' => 'https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg',

                'stock' => 50,

                'average_rating' => 4.7,

                'review_count' => 38

            ],

            [

                'name' => 'Running Shoes',

                'description' => 'Professional running shoes with advanced cushioning and breathable design for optimal performance.',

                'price' => 2499.00,

                'original_price' => 3299.00,

                'category' => 'sports',

                'image' => 'https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg',

                'stock' => 25,

                'average_rating' => 4.8,

                'review_count' => 52

            ],



            // TOYS CATEGORY

            [

                'name' => 'Gaming Controller',

                'description' => 'Professional gaming controller for PS5 with customizable buttons and triggers for enhanced gaming experience.',

                'price' => 7999.00,

                'original_price' => 9999.00,

                'category' => 'toys',

                'image' => 'ssa/controller.jpg',

                'stock' => 35,

                'average_rating' => 4.7,

                'review_count' => 28

            ],



            // GROCERIES CATEGORY

            [

                'name' => 'Insulated Water Bottle',

                'description' => 'High-quality insulated water bottle that keeps drinks cold for 24 hours and hot for 12 hours.',

                'price' => 399.00,

                'original_price' => 599.00,

                'category' => 'groceries',

                'image' => 'ssa/water.jpg',

                'stock' => 60,

                'average_rating' => 4.5,

                'review_count' => 41

            ],



            // HOME CATEGORY

            [

                'name' => 'Smart Home Hub',

                'description' => 'Central smart home hub that controls all your smart devices with voice commands and mobile app.',

                'price' => 1999.00,

                'original_price' => 2499.00,

                'category' => 'home',

                'image' => 'https://m.media-amazon.com/images/I/71p5fisYQCL._AC_SL1500_.jpg',

                'stock' => 20,

                'average_rating' => 4.6,

                'review_count' => 33

            ],

            [

                'name' => 'LED Desk Lamp',

                'description' => 'Adjustable LED desk lamp with multiple brightness levels and USB charging port.',

                'price' => 599.00,

                'original_price' => 799.00,

                'category' => 'home',

                'image' => 'https://m.media-amazon.com/images/I/61x5sP-mFDL._AC_SL1500_.jpg',

                'stock' => 45,

                'average_rating' => 4.4,

                'review_count' => 26

            ],



            // BEST SELLING CATEGORY (mix of popular items)

            [

                'name' => 'Wireless Bluetooth Headphones',

                'description' => 'Premium wireless Bluetooth headphones with noise cancellation and 30-hour battery life.',

                'price' => 1299.00,

                'original_price' => 1799.00,

                'category' => 'best',

                'image' => 'https://m.media-amazon.com/images/I/71Uo4RmAdHL._AC_.jpg',

                'stock' => 100,

                'average_rating' => 4.8,

                'review_count' => 156

            ],

            [

                'name' => 'Smartphone Case',

                'description' => 'Protective smartphone case with shock absorption and wireless charging compatibility.',

                'price' => 299.00,

                'original_price' => 399.00,

                'category' => 'best',

                'image' => 'https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg',

                'stock' => 200,

                'average_rating' => 4.6,

                'review_count' => 234

            ],

            [

                'name' => 'Portable Phone Charger',

                'description' => 'High-capacity portable phone charger with fast charging technology and LED indicator.',

                'price' => 599.00,

                'original_price' => 799.00,

                'category' => 'best',

                'image' => 'https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg',

                'stock' => 150,

                'average_rating' => 4.7,

                'review_count' => 189

            ],



            // NEW ARRIVALS CATEGORY

            [

                'name' => 'Latest Smartphone Model',

                'description' => 'Brand new smartphone with advanced features, high-resolution camera, and long battery life.',

                'price' => 15999.00,

                'original_price' => 19999.00,

                'category' => 'new',

                'image' => 'https://m.media-amazon.com/images/I/71p5fisYQCL._AC_SL1500_.jpg',

                'stock' => 15,

                'average_rating' => 4.9,

                'review_count' => 12

            ],

            [

                'name' => 'AI-Powered Smart Speaker',

                'description' => 'Next-generation AI-powered smart speaker with advanced voice recognition and smart home integration.',

                'price' => 2999.00,

                'original_price' => 3999.00,

                'category' => 'new',

                'image' => 'https://m.media-amazon.com/images/I/61x5sP-mFDL._AC_SL1500_.jpg',

                'stock' => 25,

                'average_rating' => 4.8,

                'review_count' => 8

            ]

        ];



        foreach ($products as $productData) {

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

                'comment' => 'Excellent product! Exceeds all my expectations. Highly recommended!'

            ],

            [

                'rating' => 4,

                'comment' => 'Great quality and fast delivery. Very satisfied with my purchase.'

            ],

            [

                'rating' => 5,

                'comment' => 'Perfect for my needs. Will definitely buy again from this store.'

            ],

            [

                'rating' => 4,

                'comment' => 'Good product with reasonable price. Delivery was quick and packaging was excellent.'

            ],

            [

                'rating' => 5,

                'comment' => 'Amazing quality! This is exactly what I was looking for. Thank you!'

            ]

        ];



        // Create 2-4 random reviews for each product

        $numReviews = rand(2, 4);

        $selectedReviews = array_slice($reviews, 0, $numReviews);



        foreach ($selectedReviews as $reviewData) {

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


