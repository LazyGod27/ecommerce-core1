@extends('layouts.frontend')

@section('title', 'iMarket - Delete Account')

@section('styles')
<style>
    .delete-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .delete-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .delete-header {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .delete-content {
        padding: 40px;
    }

    .warning-box {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        border-left: 4px solid #f39c12;
    }

    .warning-title {
        font-weight: 600;
        color: #856404;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .warning-text {
        color: #856404;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .consequences-list {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .consequences-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .consequence-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 10px;
        color: #666;
        font-size: 0.9rem;
    }

    .consequence-item:last-child {
        margin-bottom: 0;
    }

    .consequence-icon {
        color: #dc3545;
        margin-top: 2px;
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
        border-color: #dc3545;
        background: white;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
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

    .btn-danger {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
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

    .required-field::after {
        content: " *";
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .delete-content {
            padding: 30px 20px;
        }

        .btn-container {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="delete-container">
    <div class="container mx-auto px-4">
        <div class="delete-card">
            <div class="delete-header">
                <h1 class="text-3xl font-bold mb-2">Delete Account</h1>
                <p class="text-lg opacity-90">This action cannot be undone</p>
            </div>

            <div class="delete-content">
                <div class="warning-box">
                    <div class="warning-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        ⚠️ Warning: Irreversible Action
                    </div>
                    <div class="warning-text">
                        Deleting your account will permanently remove all your data, including order history, 
                        profile information, and preferences. This action cannot be undone.
                    </div>
                </div>

                <div class="consequences-list">
                    <div class="consequences-title">
                        <i class="fas fa-times-circle text-red-500"></i>
                        What will be deleted:
                    </div>
                    <div class="consequence-item">
                        <i class="fas fa-user consequence-icon"></i>
                        <span>Your profile information and personal data</span>
                    </div>
                    <div class="consequence-item">
                        <i class="fas fa-shopping-bag consequence-icon"></i>
                        <span>All order history and purchase records</span>
                    </div>
                    <div class="consequence-item">
                        <i class="fas fa-star consequence-icon"></i>
                        <span>Product reviews and ratings you've submitted</span>
                    </div>
                    <div class="consequence-item">
                        <i class="fas fa-heart consequence-icon"></i>
                        <span>Wishlist items and saved preferences</span>
                    </div>
                    <div class="consequence-item">
                        <i class="fas fa-address-book consequence-icon"></i>
                        <span>Address information and shipping details</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.destroy') }}" id="delete-form">
                    @csrf
                    @method('DELETE')

                    <div class="form-group">
                        <label for="confirmation_text" class="form-label required-field">
                            Type "DELETE" to confirm
                        </label>
                        <input type="text" id="confirmation_text" name="confirmation_text" 
                               class="form-input @error('confirmation_text') border-red-500 @enderror"
                               placeholder="Type DELETE to confirm account deletion" required>
                        @error('confirmation_text')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label required-field">
                            Enter your password
                        </label>
                        <input type="password" id="password" name="password" 
                               class="form-input @error('password') border-red-500 @enderror"
                               placeholder="Enter your current password" required>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-container">
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-danger" id="delete-btn" disabled>
                            <i class="fas fa-trash mr-2"></i>
                            Permanently Delete Account
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
        const confirmationInput = document.getElementById('confirmation_text');
        const passwordInput = document.getElementById('password');
        const deleteBtn = document.getElementById('delete-btn');

        function validateForm() {
            const confirmation = confirmationInput.value.trim();
            const password = passwordInput.value.trim();
            
            // Enable delete button only if both fields are filled and confirmation is correct
            deleteBtn.disabled = !(confirmation === 'DELETE' && password.length > 0);
        }

        confirmationInput.addEventListener('input', validateForm);
        passwordInput.addEventListener('input', validateForm);

        // Initial validation
        validateForm();

        // Add confirmation dialog
        document.getElementById('delete-form').addEventListener('submit', function(e) {
            if (!confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
