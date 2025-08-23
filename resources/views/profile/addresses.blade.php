@extends('layouts.frontend')

@section('title', 'iMarket - Manage Addresses')

@section('styles')
<style>
    .address-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .address-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .address-header {
        background: linear-gradient(135deg, #4bc5ec 0%, #2c3c8c 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .address-content {
        padding: 40px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-title {
        font-size: 1.3rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
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

    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e1e5e9;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f8f9fa;
        cursor: pointer;
    }

    .form-select:focus {
        outline: none;
        border-color: #4bc5ec;
        background: white;
        box-shadow: 0 0 0 3px rgba(75, 197, 236, 0.1);
    }

    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e1e5e9;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f8f9fa;
        resize: vertical;
        min-height: 100px;
    }

    .form-textarea:focus {
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

    .current-address {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        border-left: 4px solid #4bc5ec;
    }

    .address-preview {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        border: 2px solid #e9ecef;
    }

    .address-line {
        margin-bottom: 8px;
        color: #333;
    }

    .address-line:last-child {
        margin-bottom: 0;
    }

    .required-field::after {
        content: " *";
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .address-content {
            padding: 30px 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .btn-container {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="address-container">
    <div class="container mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="address-card">
            <div class="address-header">
                <h1 class="text-3xl font-bold mb-2">Manage Addresses</h1>
                <p class="text-lg opacity-90">Update your shipping and billing addresses</p>
            </div>

            <div class="address-content">
                <!-- Current Address Display -->
                <div class="current-address">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                        Current Address
                    </h2>
                    
                    @if($user->address_line1)
                        <div class="address-preview">
                            <div class="address-line font-semibold">{{ $user->name }}</div>
                            <div class="address-line">{{ $user->address_line1 }}</div>
                            @if($user->address_line2)
                                <div class="address-line">{{ $user->address_line2 }}</div>
                            @endif
                            <div class="address-line">
                                {{ $user->city }}, {{ $user->state }} {{ $user->postal_code }}
                            </div>
                            <div class="address-line">{{ $user->country }}</div>
                        </div>
                    @else
                        <div class="text-gray-500 italic">No address information provided yet.</div>
                    @endif
                </div>

                <!-- Address Form -->
                <form method="POST" action="{{ route('profile.update-address') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h3 class="form-title">
                            <i class="fas fa-edit text-blue-500"></i>
                            Update Address Information
                        </h3>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="address_line1" class="form-label required-field">Address Line 1</label>
                                <input type="text" id="address_line1" name="address_line1" 
                                       class="form-input @error('address_line1') border-red-500 @enderror"
                                       value="{{ old('address_line1', $user->address_line1) }}" 
                                       placeholder="Street address, P.O. box, company name">
                                @error('address_line1')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address_line2" class="form-label">Address Line 2</label>
                                <input type="text" id="address_line2" name="address_line2" 
                                       class="form-input @error('address_line2') border-red-500 @enderror"
                                       value="{{ old('address_line2', $user->address_line2) }}" 
                                       placeholder="Apartment, suite, unit, building, floor, etc.">
                                @error('address_line2')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="city" class="form-label required-field">City</label>
                                <input type="text" id="city" name="city" 
                                       class="form-input @error('city') border-red-500 @enderror"
                                       value="{{ old('city', $user->city) }}" 
                                       placeholder="City or town">
                                @error('city')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="state" class="form-label required-field">State/Province</label>
                                <input type="text" id="state" name="state" 
                                       class="form-input @error('state') border-red-500 @enderror"
                                       value="{{ old('state', $user->state) }}" 
                                       placeholder="State or province">
                                @error('state')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="postal_code" class="form-label required-field">Postal Code</label>
                                <input type="text" id="postal_code" name="postal_code" 
                                       class="form-input @error('postal_code') border-red-500 @enderror"
                                       value="{{ old('postal_code', $user->postal_code) }}" 
                                       placeholder="ZIP or postal code">
                                @error('postal_code')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="country" class="form-label required-field">Country</label>
                                <select id="country" name="country" 
                                        class="form-select @error('country') border-red-500 @enderror">
                                    <option value="">Select a country</option>
                                    <option value="Philippines" {{ old('country', $user->country) == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                    <option value="United States" {{ old('country', $user->country) == 'United States' ? 'selected' : '' }}>United States</option>
                                    <option value="Canada" {{ old('country', $user->country) == 'Canada' ? 'selected' : '' }}>Canada</option>
                                    <option value="United Kingdom" {{ old('country', $user->country) == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="Australia" {{ old('country', $user->country) == 'Australia' ? 'selected' : '' }}>Australia</option>
                                    <option value="Germany" {{ old('country', $user->country) == 'Germany' ? 'selected' : '' }}>Germany</option>
                                    <option value="France" {{ old('country', $user->country) == 'France' ? 'selected' : '' }}>France</option>
                                    <option value="Japan" {{ old('country', $user->country) == 'Japan' ? 'selected' : '' }}>Japan</option>
                                    <option value="South Korea" {{ old('country', $user->country) == 'South Korea' ? 'selected' : '' }}>South Korea</option>
                                    <option value="China" {{ old('country', $user->country) == 'China' ? 'selected' : '' }}>China</option>
                                    <option value="India" {{ old('country', $user->country) == 'India' ? 'selected' : '' }}>India</option>
                                    <option value="Brazil" {{ old('country', $user->country) == 'Brazil' ? 'selected' : '' }}>Brazil</option>
                                    <option value="Mexico" {{ old('country', $user->country) == 'Mexico' ? 'selected' : '' }}>Mexico</option>
                                    <option value="Singapore" {{ old('country', $user->country) == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                    <option value="Malaysia" {{ old('country', $user->country) == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                    <option value="Thailand" {{ old('country', $user->country) == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                    <option value="Vietnam" {{ old('country', $user->country) == 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                                    <option value="Indonesia" {{ old('country', $user->country) == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                    <option value="Other" {{ old('country', $user->country) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('country')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="btn-container">
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Profile
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Update Address
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
    // Auto-fill country based on user's location (optional)
    document.addEventListener('DOMContentLoaded', function() {
        // If no country is selected, try to detect user's country
        const countrySelect = document.getElementById('country');
        if (!countrySelect.value) {
            // You can integrate with a geolocation service here
            // For now, we'll default to Philippines
            countrySelect.value = 'Philippines';
        }
    });
</script>
@endsection
