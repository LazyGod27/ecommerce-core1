<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voice & Image Search Demo - iMarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-2">🎤 Voice & Image Search Demo</h1>
                <p class="text-xl text-blue-100">Experience the future of e-commerce search</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div id="app" class="container mx-auto px-4 py-8">
        <!-- Feature Overview -->
        <div class="grid md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="text-4xl mb-4">🎤</div>
                <h3 class="text-xl font-semibold mb-2">Voice Search</h3>
                <p class="text-gray-600">Search products by speaking naturally using OpenAI Whisper</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="text-4xl mb-4">🖼️</div>
                <h3 class="text-xl font-semibold mb-2">Image Search</h3>
                <p class="text-gray-600">Find similar products by uploading images with GPT-4 Vision</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="text-4xl mb-4">🔍</div>
                <h3 class="text-xl font-semibold mb-2">Smart Results</h3>
                <p class="text-gray-600">AI-powered search with intelligent product matching</p>
            </div>
        </div>

        <!-- Search Interface -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-center mb-8">Try the Search Features</h2>
            
            <!-- Search Tabs -->
            <div class="flex justify-center mb-6">
                <button 
                    @click="activeTab = 'voice'" 
                    :class="['px-6 py-3 rounded-lg font-medium transition-colors', activeTab === 'voice' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700']"
                >
                    <i class="fas fa-microphone mr-2"></i>Voice Search
                </button>
                <button 
                    @click="activeTab = 'image'" 
                    :class="['px-6 py-3 rounded-lg font-medium transition-colors ml-4', activeTab === 'image' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700']"
                >
                    <i class="fas fa-image mr-2"></i>Image Search
                </button>
                <button 
                    @click="activeTab = 'text'" 
                    :class="['px-6 py-3 rounded-lg font-medium transition-colors ml-4', activeTab === 'text' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700']"
                >
                    <i class="fas fa-keyboard mr-2"></i>Text Search
                </button>
            </div>

            <!-- Voice Search Tab -->
            <div v-if="activeTab === 'voice'" class="text-center">
                <div class="mb-6">
                    <button 
                        @click="toggleVoiceSearch"
                        :class="[
                            'w-32 h-32 rounded-full transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300',
                            isRecording ? 'bg-red-500 hover:bg-red-600' : 'bg-blue-500 hover:bg-blue-600'
                        ]"
                        :disabled="!isVoiceSupported"
                    >
                        <div class="flex flex-col items-center justify-center h-full text-white">
                            <i :class="isRecording ? 'fas fa-stop text-4xl' : 'fas fa-microphone text-4xl'"></i>
                            <span class="text-sm mt-2">{{ isRecording ? 'Stop' : 'Click to Speak' }}</span>
                        </div>
                    </button>
                </div>
                
                <div v-if="voiceStatus" class="mb-4 p-3 rounded-lg" :class="voiceStatusClass">
                    <div class="flex items-center justify-center gap-2">
                        <i :class="voiceStatusIcon"></i>
                        <span>{{ voiceStatusMessage }}</span>
                    </div>
                </div>

                <div v-if="!isVoiceSupported" class="text-yellow-600 bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Voice search is not supported in your browser. Please use Chrome, Firefox, or Safari.
                </div>
            </div>

            <!-- Image Search Tab -->
            <div v-if="activeTab === 'image'" class="text-center">
                <div class="mb-6">
                    <button 
                        @click="triggerImageUpload"
                        class="w-32 h-32 rounded-full bg-green-500 hover:bg-green-600 text-white transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300"
                    >
                        <div class="flex flex-col items-center justify-center h-full">
                            <i class="fas fa-image text-4xl mb-2"></i>
                            <span class="text-sm">Click to Upload</span>
                        </div>
                    </button>
                </div>

                <input
                    ref="imageInput"
                    type="file"
                    accept="image/*"
                    @change="handleImageUpload"
                    class="hidden"
                />

                <div v-if="selectedImage" class="mb-4 p-4 border-2 border-dashed border-gray-300 rounded-lg">
                    <div class="flex items-center gap-4">
                        <img :src="selectedImage" alt="Selected image" class="w-20 h-20 object-cover rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Selected image for search</p>
                            <button @click="clearImage" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                        </div>
                    </div>
                </div>

                <div v-if="imageStatus" class="mb-4 p-3 rounded-lg" :class="imageStatusClass">
                    <div class="flex items-center justify-center gap-2">
                        <i :class="imageStatusIcon"></i>
                        <span>{{ imageStatusMessage }}</span>
                    </div>
                </div>
            </div>

            <!-- Text Search Tab -->
            <div v-if="activeTab === 'text'" class="text-center">
                <div class="max-w-md mx-auto">
                    <div class="flex gap-2">
                        <input
                            v-model="searchQuery"
                            @keyup.enter="performTextSearch"
                            type="text"
                            placeholder="Type your search here..."
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        >
                        <button
                            @click="performTextSearch"
                            class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200"
                        >
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div v-if="searchResults.length > 0 || hasSearched" class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">
                    Search Results
                    <span v-if="searchResults.length > 0" class="text-gray-500 text-lg">({{ searchResults.length }})</span>
                </h2>
                <button
                    @click="clearSearch"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200"
                >
                    <i class="fas fa-times mr-2"></i>Clear Search
                </button>
            </div>

            <!-- No Results -->
            <div v-if="searchResults.length === 0 && hasSearched" class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">🔍</div>
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
                            <span class="text-sm text-gray-500">{{ product.category }}</span>
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

        <!-- Sample Products Preview -->
        <div v-if="!hasSearched" class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-semibold text-center mb-8">Sample Products Available</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="product in sampleProducts"
                    :key="product.id"
                    class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden"
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
                            <span class="text-sm text-gray-500">{{ product.category }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Status -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
            <h3 class="text-lg font-semibold mb-4">API Status</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <button
                    @click="testVoiceAPI"
                    class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                >
                    <i class="fas fa-microphone text-blue-600 mr-2"></i>
                    Test Voice API
                </button>
                <button
                    @click="testImageAPI"
                    class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                >
                    <i class="fas fa-image text-green-600 mr-2"></i>
                    Test Image API
                </button>
            </div>
            <div v-if="apiStatus" class="mt-4 p-3 rounded-lg" :class="apiStatusClass">
                <i :class="apiStatusIcon"></i>
                <span class="ml-2">{{ apiStatusMessage }}</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">Voice & Image Search Demo - Built with Laravel, Vue.js, and OpenAI APIs</p>
            <p class="text-sm text-gray-500 mt-2">Ready for integration into any e-commerce project</p>
        </div>
    </footer>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    activeTab: 'voice',
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
                    imageStatus: null,
                    apiStatus: null,
                    sampleProducts: [
                        {
                            id: 1,
                            name: 'Gaming Laptop - RTX 4070',
                            description: 'High-performance gaming laptop with NVIDIA RTX 4070 graphics',
                            price: 1299.99,
                            category: 'Electronics',
                            image: '/images/laptop-hot-deals.jpg'
                        },
                        {
                            id: 2,
                            name: 'Classic Blue Denim Jeans',
                            description: 'Premium quality denim jeans with perfect fit',
                            price: 59.99,
                            category: 'Clothing',
                            image: '/images/shirt-hot-deals.jpg'
                        },
                        {
                            id: 3,
                            name: 'Wireless Bluetooth Headphones',
                            description: 'Premium wireless headphones with noise cancellation',
                            price: 89.99,
                            category: 'Electronics',
                            image: '/images/default-product.jpg'
                        }
                    ]
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
                voiceStatusIcon() {
                    const icons = {
                        'listening': 'fas fa-microphone text-blue-600',
                        'processing': 'fas fa-spinner fa-spin text-yellow-600',
                        'success': 'fas fa-check text-green-600',
                        'error': 'fas fa-times text-red-600'
                    };
                    return icons[this.voiceStatus] || 'fas fa-info text-gray-600';
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
                },
                imageStatusIcon() {
                    const icons = {
                        'uploading': 'fas fa-upload text-blue-600',
                        'processing': 'fas fa-spinner fa-spin text-yellow-600',
                        'success': 'fas fa-check text-green-600',
                        'error': 'fas fa-times text-red-600'
                    };
                    return icons[this.imageStatus] || 'fas fa-info text-gray-600';
                },
                apiStatusClass() {
                    return this.apiStatus?.type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                },
                apiStatusIcon() {
                    return this.apiStatus?.type === 'success' ? 'fas fa-check text-green-600' : 'fas fa-times text-red-600';
                },
                apiStatusMessage() {
                    return this.apiStatus?.message || '';
                }
            },
            mounted() {
                this.checkVoiceSupport();
            },
            methods: {
                checkVoiceSupport() {
                    this.isVoiceSupported = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
                },
                
                async performTextSearch() {
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
                                this.performTextSearch();
                            }, 1000);
                        } else {
                            throw new Error('No transcription received');
                        }
                        
                    } catch (error) {
                        console.error('Voice processing failed:', error);
                        this.voiceStatus = 'error';
                    }
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
                },
                
                clearSearch() {
                    this.searchQuery = '';
                    this.searchResults = [];
                    this.hasSearched = false;
                    this.voiceStatus = null;
                    this.imageStatus = null;
                },
                
                formatPrice(price) {
                    return parseFloat(price).toFixed(2);
                },
                
                async testVoiceAPI() {
                    try {
                        this.apiStatus = { type: 'loading', message: 'Testing voice API...' };
                        
                        const response = await axios.post('/api/search/voice', new FormData());
                        
                        if (response.status === 422) {
                            this.apiStatus = { type: 'success', message: '✅ Voice API is working! (Validation error expected for empty request)' };
                        } else {
                            this.apiStatus = { type: 'success', message: '✅ Voice API is working!' };
                        }
                    } catch (error) {
                        this.apiStatus = { type: 'error', message: '❌ Voice API test failed: ' + error.message };
                    }
                },
                
                async testImageAPI() {
                    try {
                        this.apiStatus = { type: 'loading', message: 'Testing image API...' };
                        
                        const response = await axios.post('/api/search/image', new FormData());
                        
                        if (response.status === 422) {
                            this.apiStatus = { type: 'success', message: '✅ Image API is working! (Validation error expected for empty request)' };
                        } else {
                            this.apiStatus = { type: 'success', message: '✅ Image API is working!' };
                        }
                    } catch (error) {
                        this.apiStatus = { type: 'error', message: '❌ Image API test failed: ' + error.message };
                    }
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
