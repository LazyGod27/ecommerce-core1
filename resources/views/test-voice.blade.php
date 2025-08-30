<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voice Search Test - iMarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Voice Search Test</h1>
            
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Test Voice Search</h2>
                
                <div class="text-center mb-6">
                    <button
                        id="test-voice-btn"
                        onclick="toggleVoiceTest()"
                        class="w-24 h-24 rounded-full bg-blue-500 hover:bg-blue-600 text-white transition-colors duration-200 flex items-center justify-center"
                    >
                        <i id="test-mic-icon" class="fas fa-microphone text-2xl"></i>
                    </button>
                    <p id="test-status" class="mt-2 text-gray-600">Click to start recording</p>
                </div>
                
                <div id="test-results" class="hidden">
                    <h3 class="font-semibold mb-2">Transcription:</h3>
                    <p id="transcription-text" class="bg-gray-100 p-3 rounded"></p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Test API Endpoint</h2>
                <p class="text-gray-600 mb-4">Test if the voice search API endpoint is working:</p>
                
                <button
                    onclick="testAPI()"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors duration-200"
                >
                    Test API Connection
                </button>
                
                <div id="api-status" class="mt-4 p-3 rounded hidden"></div>
            </div>

            <!-- Image Search Test -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Test Image Search</h2>
                <p class="text-gray-600 mb-4">Test image search functionality:</p>
                
                <div class="text-center mb-6">
                    <button
                        onclick="triggerImageTest()"
                        class="w-20 h-20 rounded-full bg-green-500 hover:bg-green-600 text-white transition-colors duration-200 flex items-center justify-center mx-auto mb-4"
                    >
                        <i class="fas fa-image text-xl"></i>
                    </button>
                    <p class="text-sm text-gray-600">Click to upload image</p>
                </div>
                
                <!-- Hidden file input -->
                <input
                    type="file"
                    id="test-image-input"
                    accept="image/*"
                    onchange="handleTestImageUpload(event)"
                    class="hidden"
                />
                
                <!-- Image preview -->
                <div id="test-image-preview" class="hidden mb-4 p-4 border-2 border-dashed border-gray-300 rounded-lg">
                    <div class="flex items-center gap-4">
                        <img id="test-preview-img" src="" alt="Preview" class="w-16 h-16 object-cover rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Selected image for testing</p>
                            <button onclick="clearTestImage()" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                        </div>
                    </div>
                </div>
                
                <div id="image-test-status" class="mt-4 p-3 rounded hidden"></div>
            </div>
        </div>
    </div>

    <script>
        let isRecording = false;
        let mediaRecorder = null;
        let audioChunks = [];

        function toggleVoiceTest() {
            if (isRecording) {
                stopRecording();
            } else {
                startRecording();
            }
        }

        async function startRecording() {
            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('Voice recording not supported in this browser');
                    return;
                }

                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];
                
                mediaRecorder.ondataavailable = (event) => {
                    audioChunks.push(event.data);
                };
                
                mediaRecorder.onstop = async () => {
                    await processTestAudio();
                };
                
                mediaRecorder.start();
                isRecording = true;
                
                // Update UI
                document.getElementById('test-voice-btn').classList.add('bg-red-500', 'hover:bg-red-600');
                document.getElementById('test-voice-btn').classList.remove('bg-blue-500', 'hover:bg-blue-600');
                document.getElementById('test-mic-icon').className = 'fas fa-stop text-2xl';
                document.getElementById('test-status').textContent = 'Recording... Click to stop';
                
                // Auto-stop after 10 seconds
                setTimeout(() => {
                    if (isRecording) {
                        stopRecording();
                    }
                }, 10000);
                
            } catch (error) {
                console.error('Failed to start recording:', error);
                alert('Failed to start recording: ' + error.message);
            }
        }

        function stopRecording() {
            if (mediaRecorder && isRecording) {
                mediaRecorder.stop();
                mediaRecorder.stream.getTracks().forEach(track => track.stop());
                isRecording = false;
                
                // Update UI
                document.getElementById('test-voice-btn').classList.remove('bg-red-500', 'hover:bg-red-600');
                document.getElementById('test-voice-btn').classList.add('bg-blue-500', 'hover:bg-blue-600');
                document.getElementById('test-mic-icon').className = 'fas fa-microphone text-2xl';
                document.getElementById('test-status').textContent = 'Processing...';
            }
        }

        async function processTestAudio() {
            try {
                const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                const formData = new FormData();
                formData.append('audio_file', audioBlob, 'test.wav');
                
                const response = await fetch('/api/search/voice', {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.transcribed_text) {
                        document.getElementById('transcription-text').textContent = data.transcribed_text;
                        document.getElementById('test-results').classList.remove('hidden');
                        document.getElementById('test-status').textContent = 'Transcription completed!';
                    } else {
                        throw new Error('No transcription received');
                    }
                } else {
                    const errorText = await response.text();
                    throw new Error(`API Error: ${response.status} - ${errorText}`);
                }
                
            } catch (error) {
                console.error('Voice processing failed:', error);
                document.getElementById('test-status').textContent = 'Error: ' + error.message;
            }
        }

        async function testAPI() {
            const statusDiv = document.getElementById('api-status');
            statusDiv.className = 'mt-4 p-3 rounded';
            statusDiv.classList.remove('hidden');
            statusDiv.textContent = 'Testing API...';
            
            try {
                const response = await fetch('/api/search/voice', {
                    method: 'POST',
                    body: new FormData() // Empty form data to test endpoint
                });
                
                if (response.status === 422) {
                    // This is expected - validation error for missing audio file
                    statusDiv.className = 'mt-4 p-3 rounded bg-green-100 text-green-800';
                    statusDiv.textContent = '✅ API endpoint is working! (Validation error expected for empty request)';
                } else if (response.ok) {
                    statusDiv.className = 'mt-4 p-3 rounded bg-green-100 text-green-800';
                    statusDiv.textContent = '✅ API endpoint is working!';
                } else {
                    statusDiv.className = 'mt-4 p-3 rounded bg-red-100 text-red-800';
                    statusDiv.textContent = `❌ API Error: ${response.status} - ${response.statusText}`;
                }
                
            } catch (error) {
                statusDiv.className = 'mt-4 p-3 rounded bg-red-100 text-red-800';
                statusDiv.textContent = '❌ Network Error: ' + error.message;
                    }
    }

    // Image search test functions
    function triggerImageTest() {
        document.getElementById('test-image-input').click();
    }

    function handleTestImageUpload(event) {
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
            document.getElementById('test-preview-img').src = e.target.result;
            document.getElementById('test-image-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
        
        // Test image search
        testImageSearch(file);
    }

    async function testImageSearch(imageFile) {
        const statusDiv = document.getElementById('image-test-status');
        statusDiv.className = 'mt-4 p-3 rounded';
        statusDiv.classList.remove('hidden');
        statusDiv.textContent = 'Testing image search...';
        
        try {
            const formData = new FormData();
            formData.append('image_search', imageFile);
            
            const response = await fetch('/api/search/image', {
                method: 'POST',
                body: formData
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    statusDiv.className = 'mt-4 p-3 rounded bg-green-100 text-green-800';
                    statusDiv.innerHTML = `
                        ✅ Image search successful!<br>
                        <strong>Image Description:</strong> ${data.image_description}<br>
                        <strong>Products Found:</strong> ${data.products.length}
                    `;
                } else {
                    throw new Error(data.message || 'Image search failed');
                }
            } else {
                const errorText = await response.text();
                throw new Error(`API Error: ${response.status} - ${errorText}`);
            }
            
        } catch (error) {
            statusDiv.className = 'mt-4 p-3 rounded bg-red-100 text-red-800';
            statusDiv.textContent = '❌ Image search failed: ' + error.message;
        }
    }

    function clearTestImage() {
        document.getElementById('test-image-preview').classList.add('hidden');
        document.getElementById('test-image-input').value = '';
        document.getElementById('image-test-status').classList.add('hidden');
    }
</script>
</body>
</html>
