@extends('layouts.frontend')

@section('title', 'iMarket - Preferences')

@section('styles')
<style>
    .preferences-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .preferences-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .preferences-header {
        background: linear-gradient(135deg, #4bc5ec 0%, #2c3c8c 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .preferences-content {
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

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .checkbox-group:hover {
        background: #e9ecef;
    }

    .checkbox-input {
        width: 20px;
        height: 20px;
        accent-color: #4bc5ec;
    }

    .checkbox-label {
        font-weight: 500;
        color: #333;
        cursor: pointer;
        flex: 1;
    }

    .checkbox-description {
        font-size: 0.875rem;
        color: #666;
        margin-top: 5px;
    }

    .preferences-info {
        background: #e3f2fd;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        border-left: 4px solid #2196f3;
    }

    .info-title {
        font-weight: 600;
        color: #1976d2;
        margin-bottom: 10px;
    }

    .info-text {
        color: #1565c0;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .preferences-content {
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
<div class="preferences-container">
    <div class="container mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="preferences-card">
            <div class="preferences-header">
                <h1 class="text-3xl font-bold mb-2">Preferences</h1>
                <p class="text-lg opacity-90">Customize your account settings and notifications</p>
            </div>

            <div class="preferences-content">
                <div class="preferences-info">
                    <div class="info-title">
                        <i class="fas fa-info-circle mr-2"></i>
                        About Preferences
                    </div>
                    <div class="info-text">
                        Manage your notification preferences, language settings, and timezone to personalize your iMarket experience. 
                        These settings help us provide you with relevant information and ensure timely updates about your orders.
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update-preferences') }}">
                    @csrf
                    @method('PUT')

                    <!-- Notification Preferences -->
                    <div class="form-section">
                        <h3 class="form-title">
                            <i class="fas fa-bell text-blue-500"></i>
                            Notification Preferences
                        </h3>

                        <div class="checkbox-group">
                            <input type="checkbox" id="email_notifications" name="email_notifications" 
                                   class="checkbox-input" value="1" 
                                   {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}>
                            <div>
                                <label for="email_notifications" class="checkbox-label">Email Notifications</label>
                                <div class="checkbox-description">
                                    Receive order updates, promotions, and important account information via email
                                </div>
                            </div>
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" id="sms_notifications" name="sms_notifications" 
                                   class="checkbox-input" value="1" 
                                   {{ old('sms_notifications', $user->sms_notifications) ? 'checked' : '' }}>
                            <div>
                                <label for="sms_notifications" class="checkbox-label">SMS Notifications</label>
                                <div class="checkbox-description">
                                    Get delivery updates and urgent notifications via text message
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Language & Regional Settings -->
                    <div class="form-section">
                        <h3 class="form-title">
                            <i class="fas fa-globe text-green-500"></i>
                            Language & Regional Settings
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
                    </div>

                    <div class="btn-container">
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Profile
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Save Preferences
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
    // Auto-detect timezone if not set
    document.addEventListener('DOMContentLoaded', function() {
        const timezoneSelect = document.getElementById('timezone');
        if (!timezoneSelect.value || timezoneSelect.value === 'UTC') {
            try {
                const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                if (userTimezone && userTimezone !== 'UTC') {
                    const options = Array.from(timezoneSelect.options).map(opt => opt.value);
                    if (options.includes(userTimezone)) {
                        timezoneSelect.value = userTimezone;
                    }
                }
            } catch (e) {
                console.log('Timezone detection failed, using UTC');
            }
        }
    });
</script>
@endsection
