<template>
  <div class="max-w-4xl mx-auto p-6">
    <!-- Search Header -->
    <div class="text-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800 mb-4">Find Your Perfect Product</h1>
      <p class="text-gray-600">Search by text, voice, or browse our categories</p>
    </div>

    <!-- Search Interface -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
      <div class="flex flex-col md:flex-row gap-4">
        <!-- Text Search -->
        <div class="flex-1 relative">
          <input
            v-model="searchQuery"
            @keyup.enter="performSearch"
            type="text"
            placeholder="Search for products..."
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <button
            @click="performSearch"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-500"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </button>
        </div>

        <!-- Voice Search Button -->
        <button
          @click="toggleVoiceSearch"
          :class="[
            'px-6 py-3 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2',
            isRecording 
              ? 'bg-red-500 text-white hover:bg-red-600' 
              : 'bg-blue-600 text-white hover:bg-blue-700'
          ]"
          :disabled="!isVoiceSupported"
        >
          <svg v-if="!isRecording" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
          </svg>
          <svg v-else class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
          {{ isRecording ? 'Stop Recording' : 'Voice Search' }}
        </button>

        <!-- Image Search Button -->
        <button
          @click="triggerImageUpload"
          class="px-6 py-3 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2 bg-green-600 text-white hover:bg-green-700"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          Image Search
        </button>
      </div>

      <!-- Hidden file input for image upload -->
      <input
        ref="imageInput"
        type="file"
        accept="image/*"
        @change="handleImageUpload"
        class="hidden"
      />
      </div>

      <!-- Voice Search Status -->
      <div v-if="voiceStatus" class="mt-4 p-3 rounded-lg" :class="voiceStatusClass">
        <div class="flex items-center gap-2">
          <svg v-if="voiceStatus === 'listening'" class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
          </svg>
          <span>{{ voiceStatusMessage }}</span>
        </div>
      </div>

      <!-- Image Search Status -->
      <div v-if="imageStatus" class="mt-4 p-3 rounded-lg" :class="imageStatusClass">
        <div class="flex items-center gap-2">
          <svg v-if="imageStatus === 'uploading'" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          <svg v-else-if="imageStatus === 'processing'" class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <svg v-else-if="imageStatus === 'success'" class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          <svg v-else-if="imageStatus === 'error'" class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
          <span>{{ imageStatusMessage }}</span>
        </div>
      </div>

      <!-- Image Preview -->
      <div v-if="selectedImage" class="mt-4 p-4 border-2 border-dashed border-gray-300 rounded-lg">
        <div class="flex items-center gap-4">
          <img :src="selectedImage" alt="Selected image" class="w-20 h-20 object-cover rounded-lg">
          <div class="flex-1">
            <p class="text-sm text-gray-600">Selected image for search</p>
            <button @click="clearImage" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
          </div>
        </div>
      </div>

      <!-- Voice Not Supported Message -->
      <div v-if="!isVoiceSupported" class="mt-4 p-3 bg-yellow-100 text-yellow-800 rounded-lg">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
          Voice search is not supported in your browser. Please use Chrome, Firefox, or Safari.
        </div>
      </div>
    </div>

    <!-- Search Results -->
    <div v-if="searchResults.length > 0 || hasSearched" class="bg-white rounded-lg shadow-lg p-6">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
          Search Results
          <span v-if="searchResults.length > 0" class="text-gray-500 text-lg">({{ searchResults.length }})</span>
        </h2>
        <div class="flex gap-2">
          <button
            @click="clearSearch"
            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200"
          >
            Clear Search
          </button>
        </div>
      </div>

      <!-- No Results -->
      <div v-if="searchResults.length === 0 && hasSearched" class="text-center py-12">
        <div class="text-gray-400 text-6xl mb-4">
          <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-semibold text-gray-800 mb-2">No products found</h3>
        <p class="text-gray-600 mb-6">We couldn't find any products matching "{{ searchQuery }}"</p>
        <button
          @click="clearSearch"
          class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200"
        >
          Try a different search
        </button>
      </div>

      <!-- Results Grid -->
      <div v-else-if="searchResults.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="product in searchResults"
          :key="product.id"
          class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300"
        >
          <div class="aspect-w-1 aspect-h-1 w-full">
            <img
              :src="product.image || '/images/default-product.jpg'"
              :alt="product.name"
              class="w-full h-48 object-cover"
            />
          </div>
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ product.name }}</h3>
            <p class="text-gray-600 text-sm mb-3">{{ product.description }}</p>
            <div class="flex items-center justify-between">
              <span class="text-xl font-bold text-blue-600">₱{{ formatPrice(product.price) }}</span>
              <button
                @click="addToCart(product)"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200"
              >
                Add to Cart
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <p class="mt-2 text-gray-600">Searching...</p>
    </div>

    <!-- Initial State -->
    <div v-if="!hasSearched && searchResults.length === 0" class="text-center py-12">
      <div class="text-gray-400 text-6xl mb-4">
        <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>
      <h3 class="text-2xl font-semibold text-gray-800 mb-2">Ready to search?</h3>
      <p class="text-gray-600">Use the search bar above to find products or try voice search!</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ProductSearch',
  data() {
    return {
      searchQuery: '',
      searchResults: [],
      isLoading: false,
      hasSearched: false,
      isRecording: false,
      voiceStatus: null,
      mediaRecorder: null,
      audioChunks: [],
      isVoiceSupported: false,
      selectedImage: null,
      imageStatus: null
    };
  },
  computed: {
    voiceStatusClass() {
      const classes = {
        'listening': 'bg-blue-100 text-blue-800',
        'processing': 'bg-yellow-100 text-yellow-800',
        'success': 'bg-green-100 text-green-800',
        'error': 'bg-red-100 text-red-800'
      };
      return classes[this.voiceStatus] || 'bg-gray-100 text-gray-800';
    },
    voiceStatusMessage() {
      const messages = {
        'listening': 'Listening... Speak now!',
        'processing': 'Processing your voice...',
        'success': 'Voice search completed!',
        'error': 'Voice search failed. Please try again.'
      };
      return messages[this.voiceStatus] || '';
    },
    
    imageStatusClass() {
      const classes = {
        'uploading': 'bg-blue-100 text-blue-800',
        'processing': 'bg-yellow-100 text-yellow-800',
        'success': 'bg-green-100 text-green-800',
        'error': 'bg-red-100 text-red-800'
      };
      return classes[this.imageStatus] || 'bg-gray-100 text-gray-800';
    },
    
    imageStatusMessage() {
      const messages = {
        'uploading': 'Uploading image...',
        'processing': 'Analyzing image...',
        'success': 'Image search completed!',
        'error': 'Image search failed. Please try again.'
      };
      return messages[this.imageStatus] || '';
    }
  },
  mounted() {
    this.checkVoiceSupport();
  },
  methods: {
    checkVoiceSupport() {
      this.isVoiceSupported = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
    },
    
    async performSearch() {
      if (!this.searchQuery.trim()) return;
      
      this.isLoading = true;
      this.hasSearched = true;
      
      try {
        const response = await axios.get('/api/products/search', {
          params: { q: this.searchQuery }
        });
        
        this.searchResults = response.data.data || [];
      } catch (error) {
        console.error('Search failed:', error);
        this.searchResults = [];
      } finally {
        this.isLoading = false;
      }
    },
    
    async toggleVoiceSearch() {
      if (this.isRecording) {
        this.stopRecording();
      } else {
        await this.startRecording();
      }
    },
    
    async startRecording() {
      try {
        this.voiceStatus = 'listening';
        this.isRecording = true;
        
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        this.mediaRecorder = new MediaRecorder(stream);
        this.audioChunks = [];
        
        this.mediaRecorder.ondataavailable = (event) => {
          this.audioChunks.push(event.data);
        };
        
        this.mediaRecorder.onstop = async () => {
          await this.processAudio();
        };
        
        this.mediaRecorder.start();
        
        // Auto-stop after 10 seconds
        setTimeout(() => {
          if (this.isRecording) {
            this.stopRecording();
          }
        }, 10000);
        
      } catch (error) {
        console.error('Failed to start recording:', error);
        this.voiceStatus = 'error';
        this.isRecording = false;
      }
    },
    
    stopRecording() {
      if (this.mediaRecorder && this.isRecording) {
        this.mediaRecorder.stop();
        this.mediaRecorder.stream.getTracks().forEach(track => track.stop());
        this.isRecording = false;
      }
    },
    
    async processAudio() {
      try {
        this.voiceStatus = 'processing';
        
        const audioBlob = new Blob(this.audioChunks, { type: 'audio/wav' });
        const formData = new FormData();
        formData.append('audio_file', audioBlob, 'voice.wav');
        
        const response = await axios.post('/api/search/voice', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        
        if (response.data.transcribed_text) {
          this.searchQuery = response.data.transcribed_text;
          this.voiceStatus = 'success';
          
          // Auto-search with transcribed text
          setTimeout(() => {
            this.performSearch();
          }, 1000);
        } else {
          throw new Error('No transcription received');
        }
        
      } catch (error) {
        console.error('Voice processing failed:', error);
        this.voiceStatus = 'error';
      }
    },
    
    clearSearch() {
      this.searchQuery = '';
      this.searchResults = [];
      this.hasSearched = false;
      this.voiceStatus = null;
    },
    
    formatPrice(price) {
      return parseFloat(price).toFixed(2);
    },
    
    addToCart(product) {
      // Implement add to cart functionality
      console.log('Adding to cart:', product);
      // You can emit an event or call a method to add to cart
    },
    
    triggerImageUpload() {
      this.$refs.imageInput.click();
    },
    
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (!file) return;
      
      // Validate file type
      if (!file.type.startsWith('image/')) {
        this.showImageError('Please select a valid image file.');
        return;
      }
      
      // Validate file size (max 5MB)
      if (file.size > 5 * 1024 * 1024) {
        this.showImageError('Image file size must be less than 5MB.');
        return;
      }
      
      // Create preview
      const reader = new FileReader();
      reader.onload = (e) => {
        this.selectedImage = e.target.result;
      };
      reader.readAsDataURL(file);
      
      // Perform image search
      this.performImageSearch(file);
    },
    
    async performImageSearch(imageFile) {
      try {
        this.imageStatus = 'uploading';
        this.isLoading = true;
        this.hasSearched = true;
        
        const formData = new FormData();
        formData.append('image_search', imageFile);
        
        const response = await axios.post('/api/search/image', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        
        if (response.data.success) {
          this.imageStatus = 'success';
          this.searchResults = response.data.products || [];
          
          // Auto-hide success message after 3 seconds
          setTimeout(() => {
            this.imageStatus = null;
          }, 3000);
        } else {
          throw new Error(response.data.message || 'Image search failed');
        }
        
      } catch (error) {
        console.error('Image search failed:', error);
        this.showImageError(error.message || 'Image search failed. Please try again.');
      } finally {
        this.isLoading = false;
      }
    },
    
    showImageError(message) {
      this.imageStatus = 'error';
      this.imageStatusMessage = message;
      
      // Auto-hide error message after 5 seconds
      setTimeout(() => {
        this.imageStatus = null;
      }, 5000);
    },
    
    clearImage() {
      this.selectedImage = null;
      this.$refs.imageInput.value = '';
      this.imageStatus = null;
    }
  }
};
</script>

<style scoped>
.aspect-w-1 {
  position: relative;
  padding-bottom: 100%;
}

.aspect-h-1 {
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}
</style>
