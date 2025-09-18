@extends('layouts.frontend')

@section('title', 'iMarket - My Profile')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<style>
    :root {
        --bg-color: #f7f9fb;
        --text-color: #0f172a;
        --main-color: #2c3c8c;
        --other-color: #2c3c8c;
    }
    
    * {
        box-sizing: border-box;
    }
    
    body {
        overflow-x: hidden;
    }
    
    .account-container {
        display: flex;
        min-height: calc(100vh - 100px);
        padding-top: 100px;
        background: var(--bg-color);
        max-width: 1200px;
        margin: 0 auto;
        gap: 20px;
    }
    
    .sidebar {
        width: 280px;
        background: white;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        padding: 30px 20px;
        position: sticky;
        top: 100px;
        height: fit-content;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        border-radius: 12px;
        flex-shrink: 0;
    }
    
    .user-info {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--main-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .user-avatar:hover {
        transform: scale(1.05);
    }
    
    .user-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .user-photo-upload {
        color: var(--main-color);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
    }
    
    .user-photo-upload:hover {
        text-decoration: underline;
    }
    
    .account-nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 20px;
        color: var(--text-color);
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 5px;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .nav-item:hover {
        background: #f0f9ff;
        color: var(--main-color);
    }
    
    .nav-item.active {
        background: var(--main-color);
        color: white;
    }
    
    .nav-item i {
        font-size: 1.2rem;
        width: 20px;
        text-align: center;
    }
    
    .content {
        flex: 1;
        padding: 30px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        min-height: 500px;
        overflow-x: hidden;
        word-wrap: break-word;
    }
    
    .content-section {
        display: none;
    }
    
    .content-section.active {
        display: block;
    }
    
    .content-section h2 {
        color: var(--text-color);
        margin-bottom: 30px;
        font-size: 1.8rem;
        font-weight: 600;
    }
    
    .profile-form, .address-form, .password-form {
        max-width: 600px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-color);
        font-weight: 500;
    }
    
    .form-group input, .form-group textarea {
        width: 100%;
        max-width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
        box-sizing: border-box;
    }
    
    .form-group input:focus, .form-group textarea:focus {
        outline: none;
        border-color: var(--main-color);
        box-shadow: 0 0 0 3px rgba(44, 60, 140, 0.1);
    }
    
    .error-input {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }
    
    .error-message {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 5px;
        display: block;
    }
    
    .save-button {
        background: var(--main-color);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .save-button:hover {
        background: #1e40af;
        transform: translateY(-2px);
    }
    
    .logout-section {
        text-align: center;
        padding: 40px 20px;
    }
    
    .logout-section h2 {
        color: #dc2626;
        margin-bottom: 20px;
    }
    
    .logout-section p {
        color: #6b7280;
        margin-bottom: 30px;
        font-size: 1.1rem;
    }
    
    .logout-section .save-button {
        background: #dc2626;
    }
    
    .logout-section .save-button:hover {
        background: #b91c1c;
    }
    
    @media (max-width: 1024px) {
        .account-container {
            max-width: 100%;
            margin: 0 10px;
            gap: 15px;
        }
        
        .sidebar {
            width: 250px;
        }
    }
    
    @media (max-width: 768px) {
        .account-container {
            flex-direction: column;
            padding-top: 80px;
            margin: 0;
            gap: 10px;
        }
        
        .sidebar {
            position: static;
            width: 100%;
            height: auto;
            max-height: none;
            border-radius: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .content {
            margin: 0;
            padding: 20px;
            border-radius: 0;
            min-height: auto;
        }
        
        .user-info {
            margin-bottom: 20px;
        }
        
        .nav-item {
            padding: 12px 15px;
            font-size: 0.9rem;
        }
        
        .form-group input, .form-group textarea {
            padding: 10px 12px;
            font-size: 0.9rem;
        }
        
        .save-button {
            padding: 10px 25px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 480px) {
        .account-container {
            padding-top: 70px;
        }
        
        .sidebar {
            padding: 20px 15px;
        }
        
        .content {
            padding: 15px;
        }
        
        .content-section h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="account-container">
    <aside class="sidebar">
        <div class="user-info">
            <div class="user-avatar" id="user-avatar-preview">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                @else
                    <i class="ri-user-line"></i>
                @endif
            </div>
            <a href="#" class="user-photo-upload" onclick="document.getElementById('profile-picture-input').click(); return false;">Change Photo</a>
            <input type="file" id="profile-picture-input" style="display: none;" accept="image/*">
        </div>
        <nav class="account-nav">
            <a href="#" class="nav-item active" data-target="profile-section">
                <i class="ri-user-line"></i> My Profile
            </a>
            <a href="#" class="nav-item" data-target="addresses-section">
                <i class="ri-map-pin-line"></i> My Addresses
            </a>
            <a href="#" class="nav-item" data-target="password-section">
                <i class="ri-lock-line"></i> Change Password
            </a>
            <a href="{{ route('tracking') }}" class="nav-item">
                <i class="ri-truck-line"></i> Track Orders
            </a>
            <a href="#" class="nav-item" data-target="logout-section">
                <i class="ri-logout-box-r-line"></i> Log Out
            </a>
        </nav>
    </aside>

    <section class="content">
        <div id="profile-section" class="content-section active">
            <h2>Edit Profile</h2>
            
            @if ($errors->any())
                <div class="alert alert-danger" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif
            
            <form class="profile-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="avatar">Profile Picture</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="@error('avatar') error-input @enderror">
                    @error('avatar')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small style="color: #6b7280; font-size: 0.875rem;">Upload a profile picture (max 2MB, JPG/PNG/GIF)</small>
                </div>
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Enter your full name" class="@error('name') error-input @enderror">
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Enter your email address" class="@error('email') error-input @enderror">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Enter your cellphone number" class="@error('phone') error-input @enderror">
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="save-button">Save Changes</button>
            </form>
        </div>
        
        <div id="addresses-section" class="content-section">
            <h2>My Addresses</h2>
            <p>Add and manage your shipping addresses here.</p>
            
            @if ($errors->any())
                <div class="alert alert-danger" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif
            
            <form class="address-form" method="POST" action="{{ route('profile.update-address') }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="address_line1">House Number/Unit No.</label>
                    <input type="text" id="address_line1" name="address_line1" value="{{ $user->address_line1 }}" placeholder="Apartment, unit, floor, etc.">
                </div>
                <div class="form-group">
                    <label for="address_line2">Street Name</label>
                    <input type="text" id="address_line2" name="address_line2" value="{{ $user->address_line2 }}" placeholder="Street Name">
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="{{ $user->city }}" placeholder="City">
                </div>
                <div class="form-group">
                    <label for="state">State/Province</label>
                    <input type="text" id="state" name="state" value="{{ $user->state }}" placeholder="State/Province">
                </div>
                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ $user->postal_code }}" placeholder="Postal Code">
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" value="{{ $user->country }}" placeholder="Country">
                </div>
                <button type="submit" class="save-button">Update Address</button>
            </form>
        </div>
        
        <div id="password-section" class="content-section">
            <h2>Change Password</h2>
            
            @if ($errors->any())
                <div class="alert alert-danger" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif
            
            <form class="password-form" method="POST" action="{{ route('profile.update-password') }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" placeholder="Enter current password">
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                </div>
                <button type="submit" class="save-button">Update Password</button>
            </form>
        </div>
        
        <div id="logout-section" class="content-section">
            <h2>Log Out</h2>
            <p>Are you sure you want to log out?</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="save-button">Log Out</button>
            </form>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navItems = document.querySelectorAll('.account-nav .nav-item');
        const sections = document.querySelectorAll('.content-section');
        const profilePictureInput = document.getElementById('profile-picture-input');
        const userAvatarPreview = document.getElementById('user-avatar-preview');

        navItems.forEach(item => {
            item.addEventListener('click', (event) => {
                if (item.getAttribute('href') === '{{ route('tracking') }}') {
                    return;
                }
                
                event.preventDefault();

                navItems.forEach(nav => nav.classList.remove('active'));
                sections.forEach(sec => sec.classList.remove('active'));

                item.classList.add('active');

                const targetId = item.getAttribute('data-target');
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.classList.add('active');
                }
            });
        });

        profilePictureInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '50%';
                    userAvatarPreview.innerHTML = '';
                    userAvatarPreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    });
</script>
@endsection