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
- `tracking.html` → `resources/views/tracking.blade.php` ✅ **NEW**
- `jeans.html` → `resources/views/products/category.blade.php` (generic category template)
- `accesories.html` → `resources/views/products/category.blade.php` (generic category template)
- `gaming.html` → `resources/views/products/category.blade.php` (generic category template)
- `make-up.html` → `resources/views/products/category.blade.php` (generic category template)

### 3. New Views Created
- `resources/views/layouts/frontend.blade.php` - Main layout template
- `resources/views/products/search.blade.php` - Product search results
- `resources/views/products/category.blade.php` - Product category pages (dynamic)
- `resources/views/profile/index.blade.php` - Enhanced user profile dashboard ✅ **NEW**
- `resources/views/profile/addresses.blade.php` - Address management page ✅ **NEW**

### 4. Controllers Updated
- `HomeController.php` - Updated to work with new frontend
- `CartController.php` - Updated to use session-based cart
- `AuthController.php` - Added registration functionality and social login
- `ProductController.php` - New controller for product search and categories
- `ProfileController.php` - New comprehensive profile management controller ✅ **NEW**

### 5. Routes Added
- Product search: `/products/search`
- Product categories: `/products/category/{category}`
- User registration: `/register`
- Profile management: `/profile` ✅ **NEW**
- Address management: `/profile/addresses` ✅ **NEW**
- Order tracking: `/tracking` ✅ **NEW**
- Checkout processing: `/checkout/process`
- Social login: `/auth/google`, `/auth/facebook`

## Key Features

### 1. Responsive Design
The frontend uses Tailwind CSS and is fully responsive across all devices.

### 2. Session-Based Cart
The cart system now uses Laravel sessions instead of database storage for better performance.

### 3. Authentication
- Login and registration forms in a single view
- **Social login with Google and Facebook** - fully functional OAuth integration
- User account management
- Beautiful modern authentication UI

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

### 7. Order Tracking System ✅ **NEW**
- **Real-time order tracking** with visual progress indicators
- **Sample order database** with 4 different order statuses
- **Interactive tracking interface** with search functionality
- **Order details display** including items, pricing, and shipping info
- **Progress visualization** with step-by-step status updates
- **Demo order IDs** for testing (odr1, odr2, odr3, odr4)

### 8. Enhanced User Profile System ✅ **NEW**
- **Comprehensive profile dashboard** with user statistics
- **Address management** with full CRUD operations
- **Profile completion tracking** with visual indicators
- **Recent activity feed** showing order history
- **Avatar upload and management**
- **Personal information editing** (name, email, phone, birth date, gender)
- **Notification preferences** (email, SMS)
- **Language and timezone settings**
- **Password change functionality**
- **Account deletion with confirmation**

#### Profile Features:
- **Personal Information**: Full name, email, phone, birth date, gender, age calculation
- **Address Management**: Complete address editing with validation
- **Preferences**: Email/SMS notifications, language, timezone
- **Statistics**: Total orders, reviews written, days as member
- **Activity Feed**: Recent orders and account activities
- **Profile Completion**: Visual indicator of profile completeness
- **Avatar System**: Profile picture upload and management

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
Set up file storage for product images and user avatars:
```bash
php artisan storage:link
```

### 5. Social Login Setup (Google & Facebook)

#### Google OAuth Setup:
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Go to "Credentials" → "Create Credentials" → "OAuth 2.0 Client IDs"
5. Set application type to "Web application"
6. Add authorized redirect URIs:
   - `http://localhost:8000/auth/google/callback` (for local development)
   - `https://yourdomain.com/auth/google/callback` (for production)
7. Copy the Client ID and Client Secret

#### Facebook OAuth Setup:
1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app or select existing one
3. Add Facebook Login product
4. Go to "Settings" → "Basic"
5. Add your domain to "App Domains"
6. Go to "Facebook Login" → "Settings"
7. Add Valid OAuth Redirect URIs:
   - `http://localhost:8000/auth/facebook/callback` (for local development)
   - `https://yourdomain.com/auth/facebook/callback` (for production)
8. Copy the App ID and App Secret

#### Environment Variables:
Add these to your `.env` file:
```env
# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Facebook OAuth
FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
```

### 6. Test the Integration
1. Visit the home page to see the new design
2. Click on category cards to view product pages
3. Test the search functionality
4. Try adding items to cart
5. Test the checkout process
6. **Test social login with Google and Facebook**
7. **Test order tracking with sample order IDs**
8. **Test enhanced user profile system**
9. Verify authentication works

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

## Order Tracking Features ✅ **NEW**

### Real-time Tracking
- **Visual progress indicators** with step-by-step status
- **Order status badges** with color coding
- **Progress bar animation** showing delivery progress
- **Sample order database** with realistic order data

### Order Information Display
- **Order summary** with pricing breakdown
- **Shipping details** with estimated delivery
- **Order items table** with quantities and prices
- **Address information** for shipping

### Interactive Features
- **Search functionality** to find orders by ID
- **Demo order buttons** for quick testing
- **Responsive design** for mobile and desktop
- **Real-time status updates** with visual feedback

### Sample Orders Available:
- **odr1**: Order Placed (Blue badge)
- **odr2**: Shipped (Orange badge)
- **odr3**: In Transit (Green badge)
- **odr4**: Delivered (Green badge)

## Social Login Features

### Google Login
- One-click authentication with Google account
- Automatic user creation if email doesn't exist
- Secure OAuth 2.0 flow
- User profile data synchronization

### Facebook Login
- One-click authentication with Facebook account
- Automatic user creation if email doesn't exist
- Secure OAuth 2.0 flow
- User profile data synchronization

### Authentication UI
- Modern, responsive design
- Smooth form transitions
- Error handling and validation
- Social login buttons with proper branding
- Mobile-friendly interface

## Enhanced Profile System Features ✅ **NEW**

### Profile Dashboard
- **User statistics** (orders, reviews, member days)
- **Profile completion indicator** with visual badge
- **Recent activity feed** showing order history
- **Avatar display** with placeholder for missing images
- **Personal information overview** with all user data

### Address Management
- **Complete address editing** with form validation
- **Current address display** with formatted preview
- **Country selection** with comprehensive list
- **Required field indicators** with visual cues
- **Real-time form validation** with error messages

### Profile Editing
- **Personal information** (name, email, phone, birth date, gender)
- **Avatar upload** with image validation
- **Bio and description** fields
- **Notification preferences** (email, SMS)
- **Language and timezone** settings

### Security Features
- **Password change** with current password verification
- **Account deletion** with confirmation
- **Form validation** with proper error handling
- **CSRF protection** on all forms

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
4. **Social Login**: ✅ **COMPLETED** - Google and Facebook OAuth implemented
5. **Order Tracking**: ✅ **COMPLETED** - Real-time tracking system implemented
6. **Enhanced Profile System**: ✅ **COMPLETED** - Comprehensive user profile management
7. **Admin Panel**: Create an admin interface for managing products and orders
8. **Real Product Data**: Replace dynamic generation with actual database products
9. **Product Filtering**: Add price, rating, and other filters to category pages
10. **Order Management**: Implement actual order processing and status updates

## Troubleshooting

### Common Issues

1. **Images not loading**: Ensure images are copied to `public/images/`
2. **Cart not working**: Check that sessions are properly configured
3. **Authentication errors**: Verify user table exists and has correct columns
4. **Styling issues**: Make sure `frontend.css` is being loaded
5. **Category pages not working**: Check that routes are properly defined
6. **Social login not working**: 
   - Verify OAuth credentials are correct in `.env`
   - Check redirect URIs match exactly
   - Ensure Google+ API and Facebook Login are enabled
   - Check browser console for JavaScript errors
7. **Profile features not working**:
   - Ensure all migrations have been run
   - Check that user table has all required columns
   - Verify file storage is properly configured for avatars
8. **Order tracking not working**:
   - Check that tracking routes are properly defined
   - Verify JavaScript is loading correctly
   - Test with sample order IDs (odr1, odr2, odr3, odr4)

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
5. OAuth provider settings and credentials
6. File storage configuration for avatars

## Test Credentials

After running the seeder, you can test with:
- **Email**: test@example.com
- **Password**: password

## Social Login Testing

To test social login:
1. Ensure OAuth credentials are properly configured
2. Visit `/login` page
3. Click "Continue with Google" or "Continue with Facebook"
4. Complete the OAuth flow
5. Verify user is logged in and redirected to home page

## Order Tracking Testing

To test order tracking:
1. Visit `/tracking` page
2. Use sample order IDs: odr1, odr2, odr3, odr4
3. Click the demo buttons or enter order ID manually
4. Verify order details and progress indicators display correctly

## Profile System Testing

To test the enhanced profile system:
1. Log in to your account
2. Visit `/profile` to see the dashboard
3. Test address management at `/profile/addresses`
4. Verify all profile features work correctly
5. Test avatar upload functionality
6. Check profile completion indicators
