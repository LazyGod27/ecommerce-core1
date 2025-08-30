# Voice Search Setup Guide

## Overview
This e-commerce project now includes voice search functionality powered by OpenAI's Whisper API. Users can search for products by speaking naturally instead of typing.

## Features Added
- 🎤 Voice search button in header
- 🎯 Dedicated voice search page
- 🔍 Voice-to-text transcription using OpenAI Whisper
- 🖼️ Image search functionality using OpenAI GPT-4 Vision
- 📱 Responsive design for mobile and desktop
- 🔒 Secure audio and image processing (no storage of data)

## Setup Instructions

### 1. Environment Variables
Add these to your `.env` file:

```bash
# OpenAI Configuration for Voice Search
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_ORGANIZATION_ID=your_openai_organization_id_here

# Optional: HuggingFace for AI summaries
HUGGINGFACE_API_KEY=your_huggingface_api_key_here
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Get OpenAI API Key
1. Go to [OpenAI Platform](https://platform.openai.com/)
2. Sign up or log in
3. Navigate to API Keys section
4. Create a new API key
5. Copy the key to your `.env` file

### 4. Test Voice Search
1. Start your Laravel server: `php artisan serve`
2. Visit `/voice-search` in your browser
3. Click the microphone button and speak
4. Allow microphone access when prompted

## How It Works

### Frontend (Vue.js)
- **ProductSearch.vue**: Main search component with voice functionality
- **Header Integration**: Voice search button in navigation
- **Voice Search Page**: Dedicated page for voice search

### Backend (Laravel)
- **SearchController**: Handles voice search API endpoint
- **ImageSearchController**: Handles image search API endpoint
- **OpenAI Integration**: Uses Whisper API for transcription and GPT-4 Vision for image analysis
- **Product Search**: Searches products based on transcribed text or image descriptions

### API Endpoints
- `POST /api/search/voice`: Accepts audio file and returns transcription
- `POST /api/search/image`: Accepts image file and returns similar products
- `GET /api/products/search`: Searches products by query

## Browser Compatibility
Voice search works in modern browsers that support:
- MediaRecorder API
- getUserMedia API
- Web Audio API

**Supported Browsers:**
- Chrome 66+
- Firefox 60+
- Safari 14.1+
- Edge 79+

## Troubleshooting

### Common Issues

1. **"Voice search not supported"**
   - Update your browser to the latest version
   - Ensure you're using HTTPS (required for microphone access)

2. **"Failed to start recording"**
   - Check microphone permissions in browser settings
   - Ensure microphone is not being used by another application

3. **"Voice search failed"**
   - Verify OpenAI API key is correct
   - Check API quota and billing status
   - Review Laravel logs for detailed error messages

### Debug Mode
Enable debug mode in `.env`:
```bash
APP_DEBUG=true
```

Check Laravel logs in `storage/logs/laravel.log`

## Security Considerations

- Audio files are processed in memory and not stored
- OpenAI API calls are made server-side
- No voice data is logged or persisted
- HTTPS is required for microphone access

## Performance Optimization

- Audio recording is limited to 15 seconds maximum
- Automatic timeout prevents excessive API calls
- Caching can be implemented for repeated searches

## Future Enhancements

- [ ] Voice search history
- [ ] Multi-language support
- [ ] Offline voice recognition
- [ ] Voice commands for navigation
- [ ] Integration with other speech services

## Support

If you encounter issues:
1. Check the troubleshooting section above
2. Review Laravel and browser console logs
3. Verify API key and permissions
4. Test with different browsers/devices

## License

This voice search functionality is part of the e-commerce project and follows the same license terms.
