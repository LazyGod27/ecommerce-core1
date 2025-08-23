@extends('layouts.frontend')

@section('title', 'iMarket - Login & Sign Up')

@section('styles')
<style>
    :root {
        --color-bright-cyan: #4bc5ec;
        --color-light-cyan: #94dcf4;
        --color-dark-blue: #2c3c8c;
        --color-dusty-blue-gray: #5c8c9c;
        --color-light-gray-blue: #bdccdc;
        --color-dark-blue-gray: #353c61;
    }
    .bg-bright-cyan { background-color: var(--color-bright-cyan); }
    .bg-light-cyan { background-color: var(--color-light-cyan); }
    .bg-dark-blue { background-color: var(--color-dark-blue); }
    .bg-dusty-blue-gray { background-color: var(--color-dusty-blue-gray); }
    .bg-light-gray-blue { background-color: var(--color-light-gray-blue); }
    .bg-dark-blue-gray { background-color: var(--color-dark-blue-gray); }
    
    .text-bright-cyan { color: var(--color-bright-cyan); }
    .text-light-cyan { color: var(--color-light-cyan); }
    .text-dark-blue { color: var(--color-dark-blue); }
    .text-dusty-blue-gray { color: var(--color-dusty-blue-gray); }
    .text-light-gray-blue { color: var(--color-light-gray-blue); }
    .text-dark-blue-gray { color: var(--color-dark-blue-gray); }
    
    .border-dusty-blue-gray { border-color: var(--color-dusty-blue-gray); }
    
    .hover\:bg-dark-blue:hover { background-color: var(--color-dark-blue); }
    .focus\:ring-bright-cyan:focus { --tw-ring-color: var(--color-bright-cyan); }
    
    .dark .bg-dark-blue-gray { background-color: var(--color-dark-blue-gray); }
    .dark .bg-dusty-blue-gray { background-color: var(--color-dusty-blue-gray); }
    .dark .text-light-cyan { color: var(--color-light-cyan); }
    .dark .text-light-gray-blue { color: var(--color-light-gray-blue); }
    .dark .border-light-gray-blue { border-color: var(--color-light-gray-blue); }
    .dark .bg-light-cyan { background-color: var(--color-light-cyan); }
    .dark .text-dark-blue { color: var(--color-dark-blue); }
</style>
@endsection

@section('content')
<div class="bg-light-gray-blue dark:bg-dark-blue-gray flex items-center justify-center min-h-screen p-4 transition-colors duration-500">
    <div class="bg-white dark:bg-dusty-blue-gray rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-auto transition-all duration-500 ease-in-out">
        
        <div id="login-form">
            <h2 class="text-3xl font-bold text-center text-dark-blue dark:text-light-cyan mb-6">Sign In</h2>
            
            <div class="space-y-4 mb-6">
                <button class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-white text-dark-blue font-semibold rounded-lg shadow-md border border-gray-300 hover:bg-gray-100 transition-colors duration-300">
                    <i class="fab fa-google text-lg"></i>
                    Sign In with Gmail
                </button>
                <button class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-[#3b5998] text-white font-semibold rounded-lg shadow-md hover:bg-[#2d4373] transition-colors duration-300">
                    <i class="fab fa-facebook-f text-lg"></i>
                    Sign In with Facebook
                </button>
            </div>
            
            <div class="relative flex items-center justify-center my-6">
                <div class="flex-grow border-t border-dusty-blue-gray dark:border-light-gray-blue"></div>
                <span class="flex-shrink mx-4 text-dusty-blue-gray dark:text-light-gray-blue text-sm">or</span>
                <div class="flex-grow border-t border-dusty-blue-gray dark:border-light-gray-blue"></div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="login-email" class="block text-sm font-medium text-dark-blue dark:text-light-gray-blue">Email</label>
                    <input type="email" id="login-email" name="email" class="mt-1 block w-full px-4 py-2 border border-dusty-blue-gray rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-bright-cyan dark:bg-dark-blue-gray dark:text-light-gray-blue dark:border-light-gray-blue" required>
                </div>
                <div>
                    <label for="login-password" class="block text-sm font-medium text-dark-blue dark:text-light-gray-blue">Password</label>
                    <input type="password" id="login-password" name="password" class="mt-1 block w-full px-4 py-2 border border-dusty-blue-gray rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-bright-cyan dark:bg-dark-blue-gray dark:text-light-gray-blue dark:border-light-gray-blue" required>
                </div>
                <button type="submit" class="w-full py-3 px-4 bg-bright-cyan text-white font-semibold rounded-lg shadow-lg hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-bright-cyan focus:ring-offset-2 dark:focus:ring-offset-dark-blue-gray transition-colors duration-300">
                    Sign In
                </button>
            </form>
            <p class="mt-6 text-center text-sm text-dark-blue dark:text-light-gray-blue">
                Don't have an account? 
                <a href="#" id="show-signup" class="text-dark-blue dark:text-light-cyan font-medium hover:text-bright-cyan transition-colors duration-300">
                    Sign up
                </a>
            </p>
        </div>

        <div id="signup-form" class="hidden">
            <h2 class="text-3xl font-bold text-center text-dark-blue dark:text-light-cyan mb-6">Sign Up</h2>

            <div class="space-y-4 mb-6">
                <button class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-white text-dark-blue font-semibold rounded-lg shadow-md border border-gray-300 hover:bg-gray-100 transition-colors duration-300">
                    <i class="fab fa-google text-lg"></i>
                    Sign Up with Gmail
                </button>
                <button class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-[#3b5998] text-white font-semibold rounded-lg shadow-md hover:bg-[#2d4373] transition-colors duration-300">
                    <i class="fab fa-facebook-f text-lg"></i>
                    Sign Up with Facebook
                </button>
            </div>

            <div class="relative flex items-center justify-center my-6">
                <div class="flex-grow border-t border-dusty-blue-gray dark:border-light-gray-blue"></div>
                <span class="flex-shrink mx-4 text-dusty-blue-gray dark:text-light-gray-blue text-sm">or</span>
                <div class="flex-grow border-t border-dusty-blue-gray dark:border-light-gray-blue"></div>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="signup-name" class="block text-sm font-medium text-dark-blue dark:text-light-gray-blue">Full Name</label>
                    <input type="text" id="signup-name" name="name" class="mt-1 block w-full px-4 py-2 border border-dusty-blue-gray rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-bright-cyan dark:bg-dark-blue-gray dark:text-light-gray-blue dark:border-light-gray-blue" required>
                </div>
                <div>
                    <label for="signup-email" class="block text-sm font-medium text-dark-blue dark:text-light-gray-blue">Email</label>
                    <input type="email" id="signup-email" name="email" class="mt-1 block w-full px-4 py-2 border border-dusty-blue-gray rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-bright-cyan dark:bg-dark-blue-gray dark:text-light-gray-blue dark:border-light-gray-blue" required>
                </div>
                <div>
                    <label for="signup-password" class="block text-sm font-medium text-dark-blue dark:text-light-gray-blue">Password</label>
                    <input type="password" id="signup-password" name="password" class="mt-1 block w-full px-4 py-2 border border-dusty-blue-gray rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-bright-cyan dark:bg-dark-blue-gray dark:text-light-gray-blue dark:border-light-gray-blue" required>
                </div>
                <div>
                    <label for="confirm-password" class="block text-sm font-medium text-dark-blue dark:text-light-gray-blue">Confirm Password</label>
                    <input type="password" id="confirm-password" name="password_confirmation" class="mt-1 block w-full px-4 py-2 border border-dusty-blue-gray rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-bright-cyan dark:bg-dark-blue-gray dark:text-light-gray-blue dark:border-light-gray-blue" required>
                </div>
                <button type="submit" class="w-full py-3 px-4 bg-bright-cyan text-white font-semibold rounded-lg shadow-lg hover:bg-dark-blue focus:outline-none focus:ring-2 focus:ring-bright-cyan focus:ring-offset-2 dark:focus:ring-offset-dark-blue-gray transition-colors duration-300">
                    Sign Up
                </button>
            </form>
            <p class="mt-6 text-center text-sm text-dark-blue dark:text-light-gray-blue">
                Already have an account? 
                <a href="#" id="show-login" class="text-dark-blue dark:text-light-cyan font-medium hover:text-bright-cyan transition-colors duration-300">
                    Sign in
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');
    const showSignupLink = document.getElementById('show-signup');
    const showLoginLink = document.getElementById('show-login');

    function showSignup() {
        loginForm.classList.add('hidden');
        signupForm.classList.remove('hidden');
    }

    function showLogin() {
        signupForm.classList.add('hidden');
        loginForm.classList.remove('hidden');
    }

    showSignupLink.addEventListener('click', (e) => {
        e.preventDefault();
        showSignup();
    });

    showLoginLink.addEventListener('click', (e) => {
        e.preventDefault();
        showLogin();
    });
</script>
@endsection