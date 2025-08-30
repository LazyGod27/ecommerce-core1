<header class="bg-blue-darker text-white shadow-md">
    <div class="container mx-auto flex items-center justify-between p-4 flex-wrap">
        <div class="flex items-center space-x-6">
            <a href="{{ route('home') }}" class="text-2xl font-bold">iMarket</a>
            <a href="{{ route('home') }}" class="inline-block px-6 py-2 bg-blue-dark text-white font-bold rounded-full shadow-lg hover:bg-blue-darker transition-colors duration-300">
                <i class="fas fa-home mr-2"></i> Home
            </a>
            <a href="{{ route('products') }}" class="inline-block px-6 py-2 bg-blue-dark text-white font-bold rounded-full shadow-lg hover:bg-blue-darker transition-colors duration-300">
                <i class="fas fa-box mr-2"></i> Products
            </a>
            <a href="{{ route('products.search') }}" class="inline-block px-6 py-2 bg-blue-dark text-white font-bold rounded-full shadow-lg hover:bg-blue-darker transition-colors duration-300">
                <i class="fas fa-search mr-2"></i> Search
            </a>
            <a href="{{ route('tracking') }}" class="flex items-center text-sm hover:text-gray-300 transition-colors duration-300">
                <i class="fas fa-map-marker-alt mr-2"></i> Track Your Order(s)
            </a>
        </div>
        
        <div class="flex-grow max-w-xl mx-4 hidden md:flex items-stretch mt-2 md:mt-0">
            <select class="bg-gray-200 text-gray-800 rounded-l-md px-4 text-sm focus:outline-none">
                <option>All</option>
            </select>
            <input type="text" id="header-search" placeholder="Search iMarket" class="flex-grow p-2 text-sm text-gray-800 focus:outline-none">
            <button onclick="performHeaderSearch()" class="bg-blue-light text-blue-dark p-2 rounded-r-md hover:bg-gray-blue hover:text-white transition-colors duration-300">
                <i class="fas fa-search"></i>
            </button>
            <button onclick="toggleVoiceSearch()" id="voice-search-btn" class="bg-blue-600 text-white p-2 rounded-r-md hover:bg-blue-700 transition-colors duration-300 ml-1">
                <i class="fas fa-microphone"></i>
            </button>
            <button onclick="triggerHeaderImageUpload()" id="image-search-btn" class="bg-green-600 text-white p-2 rounded-r-md hover:bg-green-700 transition-colors duration-300 ml-1">
                <i class="fas fa-image"></i>
            </button>
        </div>

        <!-- Hidden file input for header image search -->
        <input
            type="file"
            id="header-image-input"
            accept="image/*"
            onchange="handleHeaderImageUpload(event)"
            class="hidden"
        />
        </div>

        <div class="flex items-center space-x-6 mt-2 md:mt-0">
            @auth
                <a href="#" class="text-sm hidden md:block">
                    {{ Auth::user()->name }}
                </a>
            @else
                <a href="{{ route('login') }}" class="text-sm hidden md:block">
                    Sign in
                    <div class="text-xs text-gray-400">Account <i class="fas fa-chevron-down ml-1 text-xs"></i></div>
                </a>
            @endauth
            <a href="#" class="text-sm hidden md:block">
                Returns
                <div class="text-xs text-gray-400">& Orders</div>
            </a>
            <a href="{{ route('cart') }}" class="flex items-center text-sm font-bold relative">
                <i class="fas fa-shopping-cart text-xl mr-2"></i> Cart
                @auth
                    @php
                        $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">
                            {{ $cartCount }}
                        </span>
                    @endif
                @endauth
            </a>
        </div>
    </div>

    <nav class="bg-blue-dark text-white p-2 sm:px-4 text-sm z-10">
        <div class="container mx-auto flex justify-start space-x-6">
            <a href="#" class="hover:text-gray-300 transition-colors duration-300">Today's Deals</a>
            <a href="{{ route('voice-search') }}" class="hover:text-gray-300 transition-colors duration-300">
                <i class="fas fa-microphone mr-1"></i> Voice Search
            </a>
            <a href="#" class="hover:text-gray-300 transition-colors duration-300">Feedback</a>
            <a href="{{ route('customer-service') }}" class="hover:text-gray-300 transition-colors duration-300">Customer Service</a>
            <a href="#" class="hover:text-gray-300 transition-colors duration-300">Sell</a>
        </div>
    </nav>
</header>

<script>
let isRecording = false;
let mediaRecorder = null;
let audioChunks = [];

function performHeaderSearch() {
    const searchQuery = document.getElementById('header-search').value;
    if (searchQuery.trim()) {
        window.location.href = `/products/search?q=${encodeURIComponent(searchQuery)}`;
    }
}

async function toggleVoiceSearch() {
    if (isRecording) {
        stopRecording();
    } else {
        await startRecording();
    }
}

async function startRecording() {
    try {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Voice search is not supported in your browser. Please use Chrome, Firefox, or Safari.');
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
        
        // Update button appearance
        const btn = document.getElementById('voice-search-btn');
        btn.innerHTML = '<i class="fas fa-stop"></i>';
        btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        btn.classList.add('bg-red-500', 'hover:bg-red-600');
        
        // Auto-stop after 10 seconds
        setTimeout(() => {
            if (isRecording) {
                stopRecording();
            }
        }, 10000);
        
    } catch (error) {
        console.error('Failed to start recording:', error);
        alert('Failed to start voice recording. Please check your microphone permissions.');
    }
}

function stopRecording() {
    if (mediaRecorder && isRecording) {
        mediaRecorder.stop();
        mediaRecorder.stream.getTracks().forEach(track => track.stop());
        isRecording = false;
        
        // Reset button appearance
        const btn = document.getElementById('voice-search-btn');
        btn.innerHTML = '<i class="fas fa-microphone"></i>';
        btn.classList.remove('bg-red-500', 'hover:bg-red-600');
        btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
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
                document.getElementById('header-search').value = data.transcribed_text;
                // Auto-search with transcribed text
                performHeaderSearch();
            } else {
                throw new Error('No transcription received');
            }
        } else {
            throw new Error('Voice search failed');
        }
        
    } catch (error) {
        console.error('Voice processing failed:', error);
        alert('Voice search failed. Please try again.');
    }
}

// Add enter key support for search input
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('header-search');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                performHeaderSearch();
            }
        });
    }
});

// Image search functions
function triggerHeaderImageUpload() {
    document.getElementById('header-image-input').click();
}

async function handleHeaderImageUpload(event) {
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
    
    try {
        // Show loading state
        const btn = document.getElementById('image-search-btn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;
        
        const formData = new FormData();
        formData.append('image_search', file);
        
        const response = await fetch('/api/search/image', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                // Redirect to search results page with image description
                const description = encodeURIComponent(data.image_description);
                window.location.href = `/products/search?q=${description}&type=image`;
            } else {
                throw new Error(data.message || 'Image search failed');
            }
        } else {
            throw new Error('Image search failed');
        }
        
    } catch (error) {
        console.error('Image search failed:', error);
        alert('Image search failed: ' + error.message);
    } finally {
        // Reset button state
        const btn = document.getElementById('image-search-btn');
        btn.innerHTML = '<i class="fas fa-image"></i>';
        btn.disabled = false;
        
        // Clear file input
        event.target.value = '';
    }
}
</script>