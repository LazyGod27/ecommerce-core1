<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Frontend Integration Guide

This document explains how the frontend from "New folder (2)" has been integrated into the Laravel ecommerce backend.

## What Was Integrated

### 1. Static Assets
- **CSS**: The `All.css` file has been copied to `public/css/frontend.css`
- **Images**: The image files should be copied to `public/images/` (you'll need to manually copy these)

### 2. Blade Templates
The following HTML files have been converted to Blade templates:

- `index.html` → `resources/views/home.blade.php`
- `cart.html` → `resources/views/cart.blade.php`
- `login.html` → `resources/views/auth/login.blade.php`
- `jeans.html` → `resources/views/products/category.blade.php` (generic category template)
- `accesories.html` → `resources/views/products/category.blade.php` (generic category template)
- `gaming.html` → `resources/views/products/category.blade.php` (generic category template)
- `make-up.html` → `resources/views/products/category.blade.php` (generic category template)

### 3. New Views Created
- `resources/views/layouts/frontend.blade.php` - Main layout template
- `resources/views/products/search.blade.php` - Product search results
- `resources/views/products/category.blade.php` - Product category pages (dynamic)
- `resources/views/account.blade.php` - User account page

### 4. Controllers Updated
- `HomeController.php` - Updated to work with new frontend
- `CartController.php` - Updated to use session-based cart
- `AuthController.php` - Added registration functionality
- `ProductController.php` - New controller for product search and categories

### 5. Routes Added
- Product search: `/products/search`
- Product categories: `/products/category/{category}`
- User registration: `/register`
- Account page: `/account`
- Checkout processing: `/checkout/process`

## Key Features

### 1. Responsive Design
The frontend uses Tailwind CSS and is fully responsive across all devices.

### 2. Session-Based Cart
The cart system now uses Laravel sessions instead of database storage for better performance.

### 3. Authentication
- Login and registration forms in a single view
- Social login buttons (Google, Facebook) - requires additional setup
- User account management

### 4. Product Management
- Product search functionality
- Category-based product browsing with dynamic product generation
- Add to cart functionality with modal product details
- Real-time product grid with badges (XTRA, Sale, Free Shipping, COD)

### 5. Product Categories
The following categories are now available:
- **Gaming Products** (`/products/category/gaming`)
- **Accessories** (`/products/category/accessories`)
- **Clothing & Fashion** (`/products/category/clothing`)
- **Beauty & Skincare** (`/products/category/beauty`)
- **Jeans Collection** (`/products/category/jeans`)
- **Makeup & Cosmetics** (`/products/category/make-up`)

Each category page features:
- Dynamic product generation with category-specific product names
- Product modal with detailed view
- Add to cart functionality
- Sale badges and pricing
- Rating and review display
- Free shipping and COD indicators

### 6. Checkout System
- Multiple payment methods (GCash, PayMaya, Card, COD)
- Voucher code system
- Order summary with real-time calculations

## Setup Instructions

### 1. Copy Images
Manually copy the image files from "New folder (2)" to `public/images/`:
- `NIKE.png`
- `shirt hot deals.jpg`
- `laptop hot deals.jpg`
- `Black and White Modern Personal Brand Logo.png`
- `imarket.png`

### 2. Database Setup
Run the migrations to ensure all required tables exist:
```bash
php artisan migrate
```

### 3. Seed the Database
Add sample products to test the category pages:
```bash
php artisan db:seed
```

### 4. Storage Setup
Set up file storage for product images:
```bash
php artisan storage:link
```

### 5. Test the Integration
1. Visit the home page to see the new design
2. Click on category cards to view product pages
3. Test the search functionality
4. Try adding items to cart
5. Test the checkout process
6. Verify authentication works

## Category Pages Features

### Dynamic Product Generation
Each category page generates 20 products dynamically with:
- Category-specific product names
- Random pricing with sale discounts
- Product ratings and sold counts
- Visual badges (XTRA, Sale percentages)
- Free shipping and COD indicators

### Product Modal
- Click any product to open detailed modal
- Add to cart directly from modal
- Proceed to checkout option
- Responsive design for mobile and desktop

### Category-Specific Products
- **Gaming**: Gaming Mouse, Keyboard, Headset, Chair, Monitor, etc.
- **Accessories**: Earbuds, Smart Watch, Phone Case, Power Bank, etc.
- **Clothing**: Various jean styles, T-shirts, Shoes, Jackets
- **Beauty**: Lipstick, Foundation, Mascara, Skincare products

## Customization

### Colors
The color scheme is defined in CSS variables in `public/css/frontend.css`:
- Primary blue: `#4bc5ec`
- Dark blue: `#2c3c8c`
- Dark slate: `#353c61`

### Layout
The main layout is in `resources/views/layouts/frontend.blade.php` and includes:
- Header with search and navigation
- Footer with links
- Responsive design
- CSRF protection
- Authentication status

### Adding New Categories
To add a new category:
1. Add the category to the `$categoryMap` in `ProductController.php`
2. Add category-specific product names to the `categoryProducts` object in `category.blade.php`
3. Update the home page category cards if needed

## Next Steps

1. **Add Product Images**: Upload product images to `storage/app/public/`
2. **Configure Payment Gateways**: Set up actual payment processing
3. **Add Email Notifications**: Configure order confirmation emails
4. **Social Login**: Implement Google and Facebook authentication
5. **Admin Panel**: Create an admin interface for managing products and orders
6. **Real Product Data**: Replace dynamic generation with actual database products
7. **Product Filtering**: Add price, rating, and other filters to category pages

## Troubleshooting

### Common Issues

1. **Images not loading**: Ensure images are copied to `public/images/`
2. **Cart not working**: Check that sessions are properly configured
3. **Authentication errors**: Verify user table exists and has correct columns
4. **Styling issues**: Make sure `frontend.css` is being loaded
5. **Category pages not working**: Check that routes are properly defined

### Debug Mode
Enable debug mode in `.env` to see detailed error messages:
```
APP_DEBUG=true
```

## Support

If you encounter any issues with the integration, check:
1. Laravel logs in `storage/logs/`
2. Browser console for JavaScript errors
3. Network tab for failed requests
4. Database connection and migrations

## Test Credentials

After running the seeder, you can test with:
- **Email**: test@example.com
- **Password**: password
