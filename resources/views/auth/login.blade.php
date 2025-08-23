@extends('layouts.frontend')

@section('title', 'iMarket - Login & Sign Up')

@section('styles')
<style>
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
    }

    .auth-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 100%;
        max-width: 900px;
        display: flex;
        min-height: 600px;
    }

    .auth-image {
        flex: 1;
        background: linear-gradient(135deg, #4bc5ec 0%, #2c3c8c 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        padding: 40px;
        position: relative;
        overflow: hidden;
    }

    .auth-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    }

    .auth-image-content {
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .auth-image h2 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .auth-image p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .auth-forms {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .form-container {
        display: none;
    }

    .form-container.active {
        display: block;
    }

    .form-title {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 30px;
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e1e5e9;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-input:focus {
        outline: none;
        border-color: #4bc5ec;
        background: white;
        box-shadow: 0 0 0 3px rgba(75, 197, 236, 0.1);
    }

    .form-button {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #4bc5ec 0%, #2c3c8c 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .form-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(75, 197, 236, 0.3);
    }

    .social-buttons {
        margin-top: 20px;
    }

    .social-button {
        width: 100%;
        padding: 12px;
        border: 2px solid #e1e5e9;
        border-radius: 10px;
        background: white;
        color: #333;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 10px;
        text-decoration: none;
    }

    .social-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        text-decoration: none;
    }

    .social-button.google {
        border-color: #db4437;
        color: #db4437;
    }

    .social-button.google:hover {
        background: #db4437;
        color: white;
    }

    .social-button.facebook {
        border-color: #4267B2;
        color: #4267B2;
    }

    .social-button.facebook:hover {
        background: #4267B2;
        color: white;
    }

    .form-toggle {
        text-align: center;
        margin-top: 20px;
    }

    .toggle-link {
        color: #4bc5ec;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }

    .toggle-link:hover {
        text-decoration: underline;
    }

    .error-message {
        background: #fee;
        color: #c33;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #fcc;
    }

    .success-message {
        background: #efe;
        color: #363;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #cfc;
    }

    @media (max-width: 768px) {
        .auth-card {
            flex-direction: column;
            max-width: 400px;
        }

        .auth-image {
            padding: 30px 20px;
        }

        .auth-image h2 {
            font-size: 2rem;
        }

        .auth-forms {
            padding: 30px 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-image">
            <div class="auth-image-content">
                <h2>Welcome to iMarket</h2>
                <p>Your one-stop shop for everything you need</p>
            </div>
        </div>
        
        <div class="auth-forms">
            @if ($errors->any())
                <div class="error-message">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Login Form -->
            <div id="login-form" class="form-container active">
                <h2 class="form-title">Sign In</h2>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="login-email" class="form-label">Email Address</label>
                        <input type="email" id="login-email" name="email" class="form-input" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="login-password" class="form-label">Password</label>
                        <input type="password" id="login-password" name="password" class="form-input" required>
                    </div>
                    
                    <button type="submit" class="form-button">Sign In</button>
                </form>

                <div class="social-buttons">
                    <a href="{{ route('auth.google') }}" class="social-button google">
                        <i class="fab fa-google"></i>
                        Continue with Google
                    </a>
                    <a href="{{ route('auth.facebook') }}" class="social-button facebook">
                        <i class="fab fa-facebook-f"></i>
                        Continue with Facebook
                    </a>
                </div>

                <div class="form-toggle">
                    <span>Don't have an account? </span>
                    <a class="toggle-link" onclick="showRegister()">Sign Up</a>
                </div>
            </div>

            <!-- Register Form -->
            <div id="register-form" class="form-container">
                <h2 class="form-title">Create Account</h2>
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label for="register-name" class="form-label">Full Name</label>
                        <input type="text" id="register-name" name="name" class="form-input" value="{{ old('name') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="register-email" class="form-label">Email Address</label>
                        <input type="email" id="register-email" name="email" class="form-input" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="register-password" class="form-label">Password</label>
                        <input type="password" id="register-password" name="password" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="register-password-confirm" class="form-label">Confirm Password</label>
                        <input type="password" id="register-password-confirm" name="password_confirmation" class="form-input" required>
                    </div>
                    
                    <button type="submit" class="form-button">Create Account</button>
                </form>

                <div class="social-buttons">
                    <a href="{{ route('auth.google') }}" class="social-button google">
                        <i class="fab fa-google"></i>
                        Sign up with Google
                    </a>
                    <a href="{{ route('auth.facebook') }}" class="social-button facebook">
                        <i class="fab fa-facebook-f"></i>
                        Sign up with Facebook
                    </a>
                </div>

                <div class="form-toggle">
                    <span>Already have an account? </span>
                    <a class="toggle-link" onclick="showLogin()">Sign In</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showLogin() {
        document.getElementById('login-form').classList.add('active');
        document.getElementById('register-form').classList.remove('active');
    }

    function showRegister() {
        document.getElementById('login-form').classList.remove('active');
        document.getElementById('register-form').classList.add('active');
    }

    // Auto-switch to register form if there are validation errors for registration
    @if($errors->has('name') || $errors->has('password_confirmation'))
        showRegister();
    @endif
</script>
@endsection