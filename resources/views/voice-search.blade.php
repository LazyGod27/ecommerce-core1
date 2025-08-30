@extends('layouts.frontend')

@section('title', 'Voice Search - iMarket')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Voice Search</h1>
            <p class="text-xl text-gray-600">Speak naturally to find products on iMarket</p>
        </div>

        <!-- Voice Search Interface -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <!-- Voice Button -->
                <div class="text-center mb-8">
                    <button
                        id="voice-search-btn"
                        onclick="toggleVoiceSearch()"
                        class="w-32 h-32 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300"
                    >
                        <div class="flex flex-col items-center justify-center h-full text-white">
                            <svg id="mic-icon" class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                            <span id="voice-btn-text" class="text-sm font-medium">Click to Speak</span>
                        </div>
                    </button>
                </div>

                <!-- Status Messages -->
                <div id="voice-status" class="hidden mb-6 p-4 rounded-lg text-center">
                    <div id="status-content" class="flex items-center justify-center gap-3">
                        <div id="status-icon"></div>
                        <span id="status-text"></span>
                    </div>
                </div>

                <!-- Search Results -->
                <div id="search-results" class="hidden">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Search Results</h3>
                    <div id="results-content" class="space-y-4"></div>
                </div>

                <!-- Instructions -->
                <div class="text-center text-gray-600">
                    <p class="mb-2">💡 <strong>Tip:</strong> Speak clearly and naturally</p>
                    <p class="mb-2">🎯 Try saying: "Show me gaming laptops" or "Find blue jeans"</p>
                    <p>🔒 Your voice is processed securely and not stored</p>
                </div>
            </div>

            <!-- Alternative Search -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Or search by typing</h3>
                <div class="flex gap-3">
                    <input
                        type="text"
                        id="text-search"
                        placeholder="Type your search here..."
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <button
                        onclick="performTextSearch()"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
                    >
                        Search
                    </button>
                </div>
            </div>

            <!-- Image Search -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Or search by image</h3>
                <p class="text-gray-600 mb-4">Upload an image to find similar products</p>
                
                <div class="text-center">
                    <button
                        onclick="triggerImageUpload()"
                        class="w-24 h-24 rounded-full bg-green-500 hover:bg-green-600 text-white transition-colors duration-200 flex items-center justify-center mx-auto mb-4"
                    >
                        <i class="fas fa-image text-2xl"></i>
                    </button>
                    <p class="text-sm text-gray-600">Click to upload image</p>
                </div>
                
                <!-- Hidden file input -->
                <input
                    type="file"
                    id="image-input"
                    accept="image/*"
                    onchange="handleImageUpload(event)"
                    class="hidden"
                />
                
                <!-- Image preview -->
                <div id="image-preview" class="hidden mt-4 p-4 border-2 border-dashed border-gray-300 rounded-lg">
                    <div class="flex items-center gap-4">
                        <img id="preview-img" src="" alt="Preview" class="w-20 h-20 object-cover rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Selected image for search</p>
                            <button onclick="clearImage()" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let isRecording = false;
let mediaRecorder = null;
let audioChunks = [];

function toggleVoiceSearch() {
    if (isRecording) {
        stopRecording();
    } else {
        startRecording();
    }
}

async function startRecording() {
    try {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            showStatus('error', 'Voice search is not supported in your browser. Please use Chrome, Firefox, or Safari.');
            return;
        }

        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];
        
        mediaRecorder.ondataavailable = (event) => {
            audioChunks.push(event.data);
        };
        
        mediaRecorder.onstop = async () => {
            await processAudio();
        };
        
        mediaRecorder.start();
        isRecording = true;
        
        // Update UI
        updateVoiceButton(true);
        showStatus('listening', 'Listening... Speak now!');
        
        // Auto-stop after 15 seconds
        setTimeout(() => {
            if (isRecording) {
                stopRecording();
            }
        }, 15000);
        
    } catch (error) {
        console.error('Failed to start recording:', error);
        showStatus('error', 'Failed to start voice recording. Please check your microphone permissions.');
    }
}

function stopRecording() {
    if (mediaRecorder && isRecording) {
        mediaRecorder.stop();
        mediaRecorder.stream.getTracks().forEach(track => track.stop());
        isRecording = false;
        
        // Update UI
        updateVoiceButton(false);
        showStatus('processing', 'Processing your voice...');
    }
}

async function processAudio() {
    try {
        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
        const formData = new FormData();
        formData.append('audio_file', audioBlob, 'voice.wav');
        
        const response = await fetch('/api/search/voice', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.transcribed_text) {
                showStatus('success', `Heard: "${data.transcribed_text}"`);
                
                // Search for products
                await searchProducts(data.transcribed_text);
            } else {
                throw new Error('No transcription received');
            }
        } else {
            throw new Error('Voice search failed');
        }
        
    } catch (error) {
        console.error('Voice processing failed:', error);
        showStatus('error', 'Voice search failed. Please try again.');
    }
}

async function searchProducts(query) {
    try {
        const response = await fetch(`/api/products/search?q=${encodeURIComponent(query)}`);
        if (response.ok) {
            const data = await response.json();
            displayResults(data.data || [], query);
        } else {
            throw new Error('Product search failed');
        }
    } catch (error) {
        console.error('Product search failed:', error);
        showStatus('error', 'Failed to search products. Please try again.');
    }
}

function displayResults(products, query) {
    const resultsDiv = document.getElementById('search-results');
    const contentDiv = document.getElementById('results-content');
    
    if (products.length === 0) {
        contentDiv.innerHTML = `
            <div class="text-center py-8">
                <div class="text-gray-400 text-4xl mb-4">🔍</div>
                <h4 class="text-lg font-semibold text-gray-800 mb-2">No products found</h4>
                <p class="text-gray-600">We couldn't find any products matching "${query}"</p>
            </div>
        `;
    } else {
        contentDiv.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                ${products.map(product => `
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center gap-3">
                            <img src="${product.image || '/images/default-product.jpg'}" 
                                 alt="${product.name}" 
                                 class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">${product.name}</h4>
                                <p class="text-sm text-gray-600">${product.description || ''}</p>
                                <p class="text-lg font-bold text-blue-600">₱${parseFloat(product.price).toFixed(2)}</p>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    resultsDiv.classList.remove('hidden');
}

function performTextSearch() {
    const query = document.getElementById('text-search').value.trim();
    if (query) {
        searchProducts(query);
        showStatus('success', `Searching for: "${query}"`);
    }
}

function updateVoiceButton(recording) {
    const btn = document.getElementById('voice-search-btn');
    const icon = document.getElementById('mic-icon');
    const text = document.getElementById('voice-btn-text');
    
    if (recording) {
        btn.classList.remove('from-blue-500', 'to-purple-600');
        btn.classList.add('from-red-500', 'to-pink-600');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
        text.textContent = 'Stop Recording';
    } else {
        btn.classList.remove('from-red-500', 'to-pink-600');
        btn.classList.add('from-blue-500', 'to-purple-600');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>';
        text.textContent = 'Click to Speak';
    }
}

function showStatus(type, message) {
    const statusDiv = document.getElementById('voice-status');
    const iconDiv = document.getElementById('status-icon');
    const textDiv = document.getElementById('status-text');
    
    // Clear previous classes
    statusDiv.className = 'mb-6 p-4 rounded-lg text-center';
    
    // Add type-specific classes
    const typeClasses = {
        'listening': 'bg-blue-100 text-blue-800',
        'processing': 'bg-yellow-100 text-yellow-800',
        'success': 'bg-green-100 text-green-800',
        'error': 'bg-red-100 text-red-800'
    };
    
    statusDiv.classList.add(typeClasses[type] || 'bg-gray-100 text-gray-800');
    
    // Set icon and text
    const icons = {
        'listening': '🎤',
        'processing': '⏳',
        'success': '✅',
        'error': '❌'
    };
    
    iconDiv.textContent = icons[type] || 'ℹ️';
    textDiv.textContent = message;
    
    statusDiv.classList.remove('hidden');
    
    // Auto-hide success messages after 5 seconds
    if (type === 'success') {
        setTimeout(() => {
            statusDiv.classList.add('hidden');
        }, 5000);
    }
}

// Add enter key support for text search
document.addEventListener('DOMContentLoaded', function() {
    const textSearch = document.getElementById('text-search');
    if (textSearch) {
        textSearch.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                performTextSearch();
            }
        });
    }
});

// Image search functions
function triggerImageUpload() {
    document.getElementById('image-input').click();
}

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
        alert('Please select a valid image file.');
        return;
    }
    
    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('Image file size must be less than 5MB.');
        return;
    }
    
    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('image-preview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
    
    // Perform image search
    performImageSearch(file);
}

async function performImageSearch(imageFile) {
    try {
        showStatus('processing', 'Analyzing image...');
        
        const formData = new FormData();
        formData.append('image_search', imageFile);
        
        const response = await fetch('/api/search/image', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                showStatus('success', `Found products based on: "${data.image_description}"`);
                
                // Display results
                displayResults(data.products || [], data.image_description);
            } else {
                throw new Error(data.message || 'Image search failed');
            }
        } else {
            throw new Error('Image search failed');
        }
        
    } catch (error) {
        console.error('Image search failed:', error);
        showStatus('error', 'Image search failed: ' + error.message);
    }
}

function clearImage() {
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('image-input').value = '';
}
</script>
@endsection
