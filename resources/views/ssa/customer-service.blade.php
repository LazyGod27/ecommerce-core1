@extends('ssa.categories.layout')

@section('title', 'Customer Service - iMarket PH')

@section('content')
<style>
    .main-container {
        display: flex;
        gap: 2rem;
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        flex: 1;
    }
    
    .card-header {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #333;
    }
    
    .chat-container {
        height: 400px;
        display: flex;
        flex-direction: column;
    }
    
    .chat-history {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1rem;
        max-height: 300px;
    }
    
    .chat-message {
        margin-bottom: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        max-width: 80%;
        word-wrap: break-word;
    }
    
    .chat-message:not(.self-end) {
        background: #e9ecef;
        color: #333;
    }
    
    .chat-message.self-end {
        background: var(--primary-color);
        color: white;
        margin-left: auto;
    }
    
    .chat-input {
        display: flex;
        gap: 0.5rem;
    }
    
    .chat-input input {
        flex: 1;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
    }
    
    .chat-input button {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .chat-input button:hover {
        background: #0056b3;
    }
    
    .contact-info h3 {
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .contact-info p {
        color: #666;
        margin-bottom: 0.5rem;
    }
    
    .contact-info a {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .contact-info a:hover {
        text-decoration: underline;
    }
    
    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }
    
    .flex {
        display: flex;
    }
    
    .items-center {
        align-items: center;
    }
    
    .gap-2 {
        gap: 0.5rem;
    }
    
    .font-semibold {
        font-weight: 600;
    }
    
    .text-lg {
        font-size: 1.125rem;
    }
    
    .text-sm {
        font-size: 0.875rem;
    }
    
    .text-base {
        font-size: 1rem;
    }
    
    .text-gray-600 {
        color: #6b7280;
    }
    
    .mb-2 {
        margin-bottom: 0.5rem;
    }
    
    .mt-1 {
        margin-top: 0.25rem;
    }
    
    .block {
        display: block;
    }
    
    @media (max-width: 768px) {
        .main-container {
            flex-direction: column;
            gap: 1rem;
        }
        
        .card {
            padding: 1rem;
        }
        
        .chat-container {
            height: 300px;
        }
    }
</style>

<div class="main-container">
    <div class="card">
        <h2 class="card-header">Chat with an Agent</h2>
        <div class="chat-container">
            <div class="chat-history" id="chat-history">
                <div class="chat-message">Hello! How can I help you today?</div>
            </div>
            <form id="chat-form" class="chat-input">
                @csrf
                <input type="text" id="chat-message-input" placeholder="Type your message..." required>
                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    
    <div class="card">
        <h2 class="card-header">Other Ways to Contact Us</h2>
        <div class="space-y-6 text-sm contact-info">
            <div>
                <h3 class="font-semibold text-lg mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call">
                        <path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.95"></path>
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    Call Us
                </h3>
                <p class="text-gray-600">Our customer service representatives are available 24/7 to assist you.</p>
                <a href="tel:+639123456789" class="font-semibold text-base block mt-1">+63 912 345 6789</a>
            </div>
            
            <div>
                <h3 class="font-semibold text-lg mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    Frequently Asked Questions
                </h3>
                <p class="text-gray-600">Browse our help center for quick answers to common questions.</p>
                <a href="#" class="font-semibold text-base block mt-1">Go to Help Center</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-message-input');
    const chatHistory = document.getElementById('chat-history');

    chatForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const messageText = chatInput.value.trim();
        if (messageText) {
            const userMessage = document.createElement('div');
            userMessage.className = 'chat-message self-end bg-main-orange text-white';
            userMessage.textContent = messageText;
            chatHistory.appendChild(userMessage);

            chatHistory.scrollTop = chatHistory.scrollHeight;

            chatInput.value = '';
        
            setTimeout(() => {
                const agentMessage = document.createElement('div');
                agentMessage.className = 'chat-message';
                agentMessage.textContent = 'Thank you for your message. An agent will be with you shortly.';
                chatHistory.appendChild(agentMessage);
                chatHistory.scrollTop = chatHistory.scrollHeight;
            }, 1000);
        }
    });
</script>
@endsection
