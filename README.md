# 🎤 Voice & Image Search E-commerce Project

A standalone Laravel project demonstrating advanced AI-powered search capabilities using OpenAI's Whisper API for voice transcription and GPT-4 Vision for image analysis. This project is designed to be easily integrated into existing e-commerce platforms.

## ✨ Features

### 🎤 Voice Search
- **Natural Language Processing**: Search products by speaking naturally
- **OpenAI Whisper Integration**: High-accuracy speech-to-text conversion
- **Real-time Recording**: Browser-based audio recording with visual feedback
- **Auto-search**: Automatically searches products after voice transcription

### 🖼️ Image Search
- **Visual Product Discovery**: Find similar products by uploading images
- **GPT-4 Vision Analysis**: Advanced AI image understanding and description
- **Smart Matching**: Intelligent keyword extraction and product matching
- **Multiple Formats**: Supports JPEG, PNG, JPG, and GIF files

### 🔍 Smart Search
- **Multi-modal Search**: Voice, image, and text search in one interface
- **AI-Powered Results**: Intelligent product matching based on search context
- **Real-time Updates**: Live search results with loading states
- **Responsive Design**: Works seamlessly on all devices

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- MySQL/PostgreSQL database
- OpenAI API key

### 1. Clone the Project
```bash
git clone <your-repo-url>
cd ecommerce-core1
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup
Copy `.env.example` to `.env` and configure:
```bash
cp .env.example .env
```

Add your OpenAI API key:
```env
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_ORGANIZATION_ID=your_openai_organization_id_here
```

### 4. Database Setup
```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### 5. Start the Application
```bash
# Start Laravel development server
php artisan serve

# In another terminal, build frontend assets
npm run dev
```

Visit `http://localhost:8000/demo` to see the demo!

## 🎯 Demo Pages

### Main Demo (`/demo`)
- **Comprehensive Showcase**: All features in one interface
- **Interactive Testing**: Try voice, image, and text search
- **Sample Products**: Pre-loaded products for testing
- **API Status**: Test API endpoints functionality

### Voice Search (`/voice-search`)
- **Dedicated Voice Interface**: Large microphone button
- **Status Feedback**: Real-time recording and processing states
- **Alternative Search**: Text search fallback option

### Test Page (`/test-voice`)
- **API Testing**: Verify backend functionality
- **Voice Recording**: Test microphone access
- **Image Upload**: Test image search capabilities

## 🏗️ Project Structure

```
ecommerce-core1/
├── app/
│   ├── Http/Controllers/Api/
│   │   ├── SearchController.php          # Voice search API
│   │   ├── ImageSearchController.php     # Image search API
│   │   └── ProductController.php         # Product search API
│   ├── Models/
│   │   ├── Product.php                   # Product model
│   │   └── Review.php                    # Review model
│   └── Console/Commands/
│       └── ProcessVoiceSearch.php        # Voice processing command
├── resources/
│   ├── js/components/
│   │   └── ProductSearch.vue            # Main search component
│   └── views/
│       ├── demo.blade.php               # Standalone demo page
│       ├── voice-search.blade.php       # Voice search page
│       └── test-voice.blade.php         # Testing page
├── routes/
│   ├── api.php                          # API endpoints
│   └── web.php                          # Web routes
└── database/
    └── seeders/
        └── ProductSeeder.php            # Sample data
```

## 🔌 API Endpoints

### Voice Search
```http
POST /api/search/voice
Content-Type: multipart/form-data

{
  "audio_file": "audio.wav"
}
```

**Response:**
```json
{
  "transcribed_text": "gaming laptop",
  "products": [...]
}
```

### Image Search
```http
POST /api/search/image
Content-Type: multipart/form-data

{
  "image_search": "image.jpg"
}
```

**Response:**
```json
{
  "success": true,
  "image_description": "A modern gaming laptop with RGB keyboard",
  "products": [...]
}
```

### Product Search
```http
GET /api/products/search?q=gaming+laptop
```

**Response:**
```json
{
  "data": [...],
  "current_page": 1,
  "total": 10
}
```

## 🎨 Frontend Components

### ProductSearch.vue
- **Vue 3 Component**: Modern reactive interface
- **Voice Integration**: MediaRecorder API for audio capture
- **Image Upload**: File handling with preview
- **Real-time Updates**: Live search results and status

### Demo Page
- **Standalone Interface**: Complete feature showcase
- **Tabbed Navigation**: Easy switching between search types
- **Sample Data**: Pre-loaded products for testing
- **API Testing**: Built-in endpoint verification

## 🔧 Configuration

### OpenAI Settings
```php
// config/services.php
'openai' => [
    'key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION_ID'),
],
```

### File Upload Limits
```php
// Image search validation
'image_search' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
```

### Audio Recording
- **Format**: WAV (browser-compatible)
- **Duration**: 10-15 seconds maximum
- **Quality**: Optimized for speech recognition

## 🧪 Testing

### Manual Testing
1. **Voice Search**: Click microphone, speak clearly
2. **Image Search**: Upload product images
3. **Text Search**: Type search queries
4. **API Testing**: Use test buttons on demo page

### Browser Compatibility
- **Chrome**: 66+ (Full support)
- **Firefox**: 60+ (Full support)
- **Safari**: 14.1+ (Full support)
- **Edge**: 79+ (Full support)

### Common Test Scenarios
- **Voice**: "Show me gaming laptops"
- **Image**: Upload laptop, clothing, or electronics photos
- **Text**: Search for "jeans", "headphones", "coffee maker"

## 🔒 Security Features

- **Input Validation**: File type and size restrictions
- **API Key Protection**: Server-side OpenAI integration
- **No Data Storage**: Audio and images processed in memory
- **HTTPS Required**: Secure microphone access

## 📱 Responsive Design

- **Mobile-First**: Optimized for all screen sizes
- **Touch-Friendly**: Large buttons and intuitive gestures
- **Progressive Enhancement**: Works without JavaScript
- **Accessibility**: Screen reader support and keyboard navigation

## 🚀 Integration Guide

### For Existing E-commerce Projects

1. **Copy Controllers**: Add `SearchController` and `ImageSearchController`
2. **Include Routes**: Add API endpoints to your routes file
3. **Update Models**: Ensure Product model has required fields
4. **Frontend Integration**: Include Vue components in your templates
5. **Environment Setup**: Add OpenAI API configuration

### Required Database Fields
```sql
products:
- id, name, description, price, category
- image, stock, rating, reviews_count
- created_at, updated_at

reviews:
- id, product_id, user_name, rating, comment
- created_at, updated_at
```

### Dependencies to Add
```json
{
  "require": {
    "openai-php/client": "^0.8.0"
  }
}
```

## 🐛 Troubleshooting

### Common Issues

#### Voice Search Not Working
- Check microphone permissions
- Ensure HTTPS is enabled
- Verify browser compatibility
- Check OpenAI API key

#### Image Search Fails
- Verify file format (JPEG, PNG, JPG, GIF)
- Check file size (max 5MB)
- Ensure OpenAI API quota available
- Check Laravel logs for errors

#### API Endpoints Not Responding
- Verify routes are registered
- Check CORS configuration
- Ensure controllers exist
- Verify database connection

### Debug Mode
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Check logs in `storage/logs/laravel.log`

## 📈 Performance Optimization

- **Audio Limits**: 15-second maximum recording
- **Image Compression**: Automatic size optimization
- **Caching**: Redis/Memcached for repeated searches
- **Database Indexing**: Optimize product search queries

## 🔮 Future Enhancements

- [ ] **Multi-language Support**: International voice search
- [ ] **Search History**: User search analytics
- [ ] **Offline Mode**: Local speech recognition
- [ ] **Voice Commands**: Navigation and cart management
- [ ] **Advanced AI**: Custom training for product categories

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🆘 Support

- **Documentation**: Check this README and inline code comments
- **Issues**: Report bugs via GitHub issues
- **Discussions**: Use GitHub discussions for questions
- **Wiki**: Additional documentation and examples

## 🎉 Acknowledgments

- **OpenAI**: For Whisper and GPT-4 Vision APIs
- **Laravel**: For the robust PHP framework
- **Vue.js**: For the reactive frontend framework
- **TailwindCSS**: For the beautiful UI components

---

**Ready to revolutionize your e-commerce search experience?** 🚀

This project demonstrates the future of product discovery and is designed to be easily integrated into any existing e-commerce platform. Start with the demo page to see all features in action!
