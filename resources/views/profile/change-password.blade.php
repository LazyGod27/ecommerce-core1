@extends('layouts.frontend')

@section('title', 'iMarket - Change Password')

@section('styles')
<style>
    .password-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .password-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .password-header {
        background: linear-gradient(135deg, #4bc5ec 0%, #2c3c8c 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .password-content {
        padding: 40px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
        font-size: 0.9rem;
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

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    .btn-container {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4bc5ec 0%, #2c3c8c 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(75, 197, 236, 0.3);
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #333;
        border: 2px solid #e9ecef;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .password-requirements {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        border-left: 4px solid #4bc5ec;
    }

    .requirement-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .requirement-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 0.9rem;
        color: #666;
    }

    .requirement-item:last-child {
        margin-bottom: 0;
    }

    .requirement-icon {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: white;
    }

    .requirement-met {
        background: #28a745;
    }

    .requirement-unmet {
        background: #dc3545;
    }

    .required-field::after {
        content: " *";
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .password-content {
            padding: 30px 20px;
        }

        .btn-container {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="password-container">
    <div class="container mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="password-card">
            <div class="password-header">
                <h1 class="text-3xl font-bold mb-2">Change Password</h1>
                <p class="text-lg opacity-90">Update your account password for enhanced security</p>
            </div>

            <div class="password-content">
                <form method="POST" action="{{ route('profile.update-password') }}" id="password-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password" class="form-label required-field">Current Password</label>
                        <input type="password" id="current_password" name="current_password" 
                               class="form-input @error('current_password') border-red-500 @enderror"
                               placeholder="Enter your current password" required>
                        @error('current_password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label required-field">New Password</label>
                        <input type="password" id="password" name="password" 
                               class="form-input @error('password') border-red-500 @enderror"
                               placeholder="Enter your new password" required>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label required-field">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="form-input @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Confirm your new password" required>
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Requirements -->
                    <div class="password-requirements">
                        <h4 class="text-lg font-semibold text-gray-700 mb-3">Password Requirements</h4>
                        <ul class="requirement-list">
                            <li class="requirement-item">
                                <span class="requirement-icon requirement-unmet" id="req-length">✕</span>
                                <span>At least 8 characters long</span>
                            </li>
                            <li class="requirement-item">
                                <span class="requirement-icon requirement-unmet" id="req-match">✕</span>
                                <span>Passwords match</span>
                            </li>
                        </ul>
                    </div>

                    <div class="btn-container">
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Profile
                        </a>
                        <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                            <i class="fas fa-key mr-2"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submit-btn');
        const reqLength = document.getElementById('req-length');
        const reqMatch = document.getElementById('req-match');

        function validatePassword() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            let isValid = true;

            // Check length requirement
            if (password.length >= 8) {
                reqLength.className = 'requirement-icon requirement-met';
                reqLength.textContent = '✓';
            } else {
                reqLength.className = 'requirement-icon requirement-unmet';
                reqLength.textContent = '✕';
                isValid = false;
            }

            // Check match requirement
            if (password && confirm && password === confirm) {
                reqMatch.className = 'requirement-icon requirement-met';
                reqMatch.textContent = '✓';
            } else {
                reqMatch.className = 'requirement-icon requirement-unmet';
                reqMatch.textContent = '✕';
                isValid = false;
            }

            // Enable/disable submit button
            submitBtn.disabled = !isValid;
        }

        passwordInput.addEventListener('input', validatePassword);
        confirmInput.addEventListener('input', validatePassword);

        // Initial validation
        validatePassword();
    });
</script>
@endsection

