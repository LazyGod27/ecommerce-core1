#!/bin/bash

echo "🚀 Setting up Voice & Image Search E-commerce Project"
echo "=================================================="

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.2+ first."
    exit 1
fi

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed. Please install Node.js first."
    exit 1
fi

echo "✅ Prerequisites check passed"

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm install

# Check if .env file exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "⚠️  Please update .env file with your database and OpenAI API credentials"
else
    echo "✅ .env file already exists"
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Check if database configuration is set
echo "🗄️  Checking database configuration..."
if php artisan migrate:status &> /dev/null; then
    echo "✅ Database connection successful"
    
    # Run migrations
    echo "🔄 Running database migrations..."
    php artisan migrate --force
    
    # Seed the database
    echo "🌱 Seeding database with sample data..."
    php artisan db:seed --force
    
    echo "✅ Database setup completed"
else
    echo "⚠️  Database connection failed. Please check your .env file"
    echo "   Required fields: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD"
fi

# Build frontend assets
echo "🎨 Building frontend assets..."
npm run build

echo ""
echo "🎉 Setup completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Update your .env file with OpenAI API key:"
echo "   OPENAI_API_KEY=your_api_key_here"
echo ""
echo "2. Start the development server:"
echo "   php artisan serve"
echo ""
echo "3. Visit the demo page:"
echo "   http://localhost:8000/demo"
echo ""
echo "4. Test the features:"
echo "   - Voice search: Click microphone and speak"
echo "   - Image search: Upload product images"
echo "   - Text search: Type search queries"
echo ""
echo "🔗 Useful URLs:"
echo "   - Demo: /demo"
echo "   - Voice Search: /voice-search"
echo "   - Test Page: /test-voice"
echo "   - Products: /products"
echo ""
echo "📚 For more information, check the README.md file"
