@extends('layouts.frontend')

@section('title', 'iMarket - Edit Profile')

@section('styles')
<style>
    .profile-edit-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .profile-edit-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .profile-edit-header {
        background: linear-gradient(135deg, #4bc5ec 0%, #2c3c8c 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .profile-edit-content {
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

    .avatar-section {
        text-align: center;
        margin-bottom: 30px;
    }

    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #e1e5e9;
        object-fit: cover;
        margin: 0 auto 20px;
        display: block;
    }

    .avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #e1e5e9;
        background: #f8f9fa;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #ccc;
    }

    .avatar-upload {
        display: inline-block;
        padding: 10px 20px;
        background: #4bc5ec;
        color: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .avatar-upload:hover {
        background: #2c3c8c;
        transform: translateY(-2px);
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .checkbox-input {
        width: 18px;
        height: 18px;
        accent-color: #4bc5ec;
    }

    .required-field::after {
        content: " *";
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .profile-edit-content {
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
<div class="profile-edit-container">
    <div class="container mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="profile-edit-card">
            <div class="profile-edit-header">
                <h1 class="text-3xl font-bold mb-2">Edit Profile</h1>
                <p class="text-lg opacity-90">Update your personal information and preferences</p>
            </div>

            <div class="profile-edit-content">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Section -->
                    <div class="avatar-section">
                        <h3 class="form-title">
                            <i class="fas fa-camera text-blue-500"></i>
                            Profile Picture
                        </h3>
                        
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="avatar-preview" id="avatar-preview">
                        @else
                            <div class="avatar-placeholder" id="avatar-preview">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif

                        <label for="avatar" class="avatar-upload">
                            <i class="fas fa-upload mr-2"></i>
                            Upload New Photo
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                        
                        <p class="text-sm text-gray-600 mt-2">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF</p>
                        @error('avatar')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Personal Information -->
                    <div class="form-section">
                        <h3 class="form-title">
                            <i class="fas fa-user text-blue-500"></i>
                            Personal Information
                        </h3>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label required-field">Full Name</label>
                                <input type="text" id="name" name="name" 
                                       class="form-input @error('name') border-red-500 @enderror"
                                       value="{{ old('name', $user->name) }}" 
                                       placeholder="Enter your full name">
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label required-field">Email Address</label>
                                <input type="email" id="email" name="email" 
                                       class="form-input @error('email') border-red-500 @enderror"
                                       value="{{ old('email', $user->email) }}" 
                                       placeholder="Enter your email address">
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" id="phone" name="phone" 
                                       class="form-input @error('phone') border-red-500 @enderror"
                                       value="{{ old('phone', $user->phone) }}" 
                                       placeholder="Enter your phone number">
                                @error('phone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="birth_date" class="form-label">Date of Birth</label>
                                <input type="date" id="birth_date" name="birth_date" 
                                       class="form-input @error('birth_date') border-red-500 @enderror"
                                       value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}">
                                @error('birth_date')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gender" class="form-label">Gender</label>
                                <select id="gender" name="gender" 
                                        class="form-select @error('gender') border-red-500 @enderror">
                                    <option value="">Select gender</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Bio Section -->
                    <div class="form-section">
                        <h3 class="form-title">
                            <i class="fas fa-edit text-green-500"></i>
                            Bio & Description
                        </h3>

                        <div class="form-group">
                            <label for="bio" class="form-label">About Me</label>
                            <textarea id="bio" name="bio" 
                                      class="form-textarea @error('bio') border-red-500 @enderror"
                                      placeholder="Tell us a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Preferences Section -->
                    <div class="form-section">
                        <h3 class="form-title">
                            <i class="fas fa-cog text-purple-500"></i>
                            Preferences
                        </h3>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="preferred_language" class="form-label">Preferred Language</label>
                                <select id="preferred_language" name="preferred_language" 
                                        class="form-select @error('preferred_language') border-red-500 @enderror">
                                    <option value="en" {{ old('preferred_language', $user->preferred_language) == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="es" {{ old('preferred_language', $user->preferred_language) == 'es' ? 'selected' : '' }}>Spanish</option>
                                    <option value="fr" {{ old('preferred_language', $user->preferred_language) == 'fr' ? 'selected' : '' }}>French</option>
                                    <option value="de" {{ old('preferred_language', $user->preferred_language) == 'de' ? 'selected' : '' }}>German</option>
                                    <option value="ja" {{ old('preferred_language', $user->preferred_language) == 'ja' ? 'selected' : '' }}>Japanese</option>
                                    <option value="ko" {{ old('preferred_language', $user->preferred_language) == 'ko' ? 'selected' : '' }}>Korean</option>
                                    <option value="zh" {{ old('preferred_language', $user->preferred_language) == 'zh' ? 'selected' : '' }}>Chinese</option>
                                </select>
                                @error('preferred_language')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select id="timezone" name="timezone" 
                                        class="form-select @error('timezone') border-red-500 @enderror">
                                    <option value="UTC" {{ old('timezone', $user->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="Asia/Manila" {{ old('timezone', $user->timezone) == 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila (Philippines)</option>
                                    <option value="America/New_York" {{ old('timezone', $user->timezone) == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                    <option value="America/Los_Angeles" {{ old('timezone', $user->timezone) == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles</option>
                                    <option value="Europe/London" {{ old('timezone', $user->timezone) == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                    <option value="Europe/Paris" {{ old('timezone', $user->timezone) == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                                    <option value="Asia/Tokyo" {{ old('timezone', $user->timezone) == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                                    <option value="Asia/Seoul" {{ old('timezone', $user->timezone) == 'Asia/Seoul' ? 'selected' : '' }}>Asia/Seoul</option>
                                    <option value="Asia/Shanghai" {{ old('timezone', $user->timezone) == 'Asia/Shanghai' ? 'selected' : '' }}>Asia/Shanghai</option>
                                </select>
                                @error('timezone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">Notification Preferences</h4>
                            
                            <div class="checkbox-group">
                                <input type="checkbox" id="email_notifications" name="email_notifications" 
                                       class="checkbox-input" value="1" 
                                       {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}>
                                <label for="email_notifications" class="form-label">Email Notifications</label>
                            </div>

                            <div class="checkbox-group">
                                <input type="checkbox" id="sms_notifications" name="sms_notifications" 
                                       class="checkbox-input" value="1" 
                                       {{ old('sms_notifications', $user->sms_notifications) ? 'checked' : '' }}>
                                <label for="sms_notifications" class="form-label">SMS Notifications</label>
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
                            Save Changes
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
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace placeholder with image
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Avatar Preview';
                    img.className = 'avatar-preview';
                    img.id = 'avatar-preview';
                    preview.parentNode.replaceChild(img, preview);
                }
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Auto-fill timezone based on user's location
    document.addEventListener('DOMContentLoaded', function() {
        const timezoneSelect = document.getElementById('timezone');
        if (!timezoneSelect.value || timezoneSelect.value === 'UTC') {
            // Try to detect user's timezone
            try {
                const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                if (userTimezone && userTimezone !== 'UTC') {
                    // Check if the detected timezone is in our options
                    const options = Array.from(timezoneSelect.options).map(opt => opt.value);
                    if (options.includes(userTimezone)) {
                        timezoneSelect.value = userTimezone;
                    }
                }
            } catch (e) {
                // Fallback to UTC if timezone detection fails
                console.log('Timezone detection failed, using UTC');
            }
        }
    });
</script>
@endsection
