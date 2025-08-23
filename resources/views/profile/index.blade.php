@extends('layouts.frontend')

@section('title', 'iMarket - My Profile')

@section('styles')
<style>
    .profile-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .profile-header {
        background: linear-gradient(135deg, #4bc5ec 0%, #2c3c8c 100%);
        color: white;
        padding: 40px;
        text-align: center;
        position: relative;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        object-fit: cover;
        margin: 0 auto 20px;
        display: block;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        background: rgba(255, 255, 255, 0.2);
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: white;
    }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        backdrop-filter: blur(10px);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .profile-content {
        padding: 40px;
    }

    .profile-section {
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        border-left: 4px solid #4bc5ec;
    }

    .info-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .info-value {
        font-size: 1.1rem;
        color: #333;
        font-weight: 500;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 30px;
    }

    .action-btn {
        padding: 15px 25px;
        border-radius: 10px;
        text-decoration: none;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4bc5ec 0%, #2c3c8c 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(75, 197, 236, 0.3);
        color: white;
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #333;
        border: 2px solid #e9ecef;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        color: #333;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
        color: white;
    }

    .completion-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: 10px;
    }

    .badge-complete {
        background: #d4edda;
        color: #155724;
    }

    .badge-incomplete {
        background: #f8d7da;
        color: #721c24;
    }

    .recent-activity {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-top: 30px;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
    }

    .activity-time {
        font-size: 0.9rem;
        color: #666;
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 30px 20px;
        }

        .profile-content {
            padding: 30px 20px;
        }

        .profile-stats {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="container mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Profile Header -->
        <div class="profile-card">
            <div class="profile-header">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="profile-avatar">
                @else
                    <div class="profile-avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif

                <h1 class="text-3xl font-bold mb-2">{{ $user->name }}</h1>
                <p class="text-lg opacity-90 mb-2">{{ $user->email }}</p>
                <p class="text-sm opacity-75">Member since {{ $user->created_at->format('F Y') }}</p>

                <div class="completion-badge {{ $user->has_complete_profile ? 'badge-complete' : 'badge-incomplete' }}">
                    <i class="fas {{ $user->has_complete_profile ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
                    {{ $user->has_complete_profile ? 'Profile Complete' : 'Profile Incomplete' }}
                </div>

                <!-- Profile Stats -->
                <div class="profile-stats">
                    <div class="stat-card">
                        <div class="stat-number">{{ $user->orders()->count() }}</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $user->reviews()->count() }}</div>
                        <div class="stat-label">Reviews Written</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $user->created_at->diffInDays(now()) }}</div>
                        <div class="stat-label">Days as Member</div>
                    </div>
                </div>
            </div>

            <div class="profile-content">
                <!-- Personal Information -->
                <div class="profile-section">
                    <h2 class="section-title">
                        <i class="fas fa-user text-blue-500"></i>
                        Personal Information
                    </h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">{{ $user->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">{{ $user->phone ?: 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date of Birth</div>
                            <div class="info-value">{{ $user->birth_date ? $user->birth_date->format('M d, Y') : 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Gender</div>
                            <div class="info-value">{{ $user->gender ? ucfirst($user->gender) : 'Not specified' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Age</div>
                            <div class="info-value">{{ $user->age ? $user->age . ' years' : 'Not provided' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="profile-section">
                    <h2 class="section-title">
                        <i class="fas fa-map-marker-alt text-green-500"></i>
                        Address Information
                    </h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Address Line 1</div>
                            <div class="info-value">{{ $user->address_line1 ?: 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Address Line 2</div>
                            <div class="info-value">{{ $user->address_line2 ?: 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">City</div>
                            <div class="info-value">{{ $user->city ?: 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">State/Province</div>
                            <div class="info-value">{{ $user->state ?: 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Postal Code</div>
                            <div class="info-value">{{ $user->postal_code ?: 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Country</div>
                            <div class="info-value">{{ $user->country ?: 'Not provided' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="profile-section">
                    <h2 class="section-title">
                        <i class="fas fa-cog text-purple-500"></i>
                        Preferences
                    </h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Email Notifications</div>
                            <div class="info-value">{{ $user->email_notifications ? 'Enabled' : 'Disabled' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">SMS Notifications</div>
                            <div class="info-value">{{ $user->sms_notifications ? 'Enabled' : 'Disabled' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Preferred Language</div>
                            <div class="info-value">{{ strtoupper($user->preferred_language) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Timezone</div>
                            <div class="info-value">{{ $user->timezone }}</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('profile.edit') }}" class="action-btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit Profile
                    </a>
                    <a href="{{ route('profile.addresses') }}" class="action-btn btn-secondary">
                        <i class="fas fa-map-marker-alt"></i>
                        Manage Addresses
                    </a>
                    <a href="{{ route('profile.orders') }}" class="action-btn btn-secondary">
                        <i class="fas fa-shopping-bag"></i>
                        View Orders
                    </a>
                    <a href="{{ route('profile.preferences') }}" class="action-btn btn-secondary">
                        <i class="fas fa-cog"></i>
                        Preferences
                    </a>
                    <a href="{{ route('profile.change-password') }}" class="action-btn btn-secondary">
                        <i class="fas fa-key"></i>
                        Change Password
                    </a>
                    <a href="{{ route('profile.delete-account') }}" class="action-btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Delete Account
                    </a>
                </div>

                <!-- Recent Activity -->
                <div class="recent-activity">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-clock mr-2"></i>
                        Recent Activity
                    </h3>
                    
                    @if($user->orders()->count() > 0)
                        @foreach($user->orders()->latest()->take(3)->get() as $order)
                        <div class="activity-item">
                            <div class="activity-icon bg-blue-500">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Order #{{ $order->id }} placed</div>
                                <div class="activity-time">{{ $order->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="activity-item">
                            <div class="activity-icon bg-gray-400">
                                <i class="fas fa-info"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">No recent activity</div>
                                <div class="activity-time">Start shopping to see your activity here</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
