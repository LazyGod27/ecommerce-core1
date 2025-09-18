<!DOCTYPE html>
<html>
<head>
    <title>Debug Authentication</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug-info { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .info { background: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>
    <h1>Authentication Debug Page</h1>
    
    <div class="debug-info {{ Auth::check() ? 'success' : 'error' }}">
        <h3>Authentication Status</h3>
        <p><strong>Logged In:</strong> {{ Auth::check() ? 'Yes' : 'No' }}</p>
        @if(Auth::check())
            <p><strong>User ID:</strong> {{ Auth::id() }}</p>
            <p><strong>User Name:</strong> {{ Auth::user()->name }}</p>
            <p><strong>User Email:</strong> {{ Auth::user()->email }}</p>
        @endif
    </div>

    <div class="debug-info info">
        <h3>Session Information</h3>
        <p><strong>Session ID:</strong> {{ session()->getId() }}</p>
        <p><strong>Session Data:</strong></p>
        <pre>{{ print_r(session()->all(), true) }}</pre>
    </div>

    <div class="debug-info info">
        <h3>Test Login Form</h3>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <p>
                <label>Email:</label><br>
                <input type="email" name="email" value="test@example.com" required>
            </p>
            <p>
                <label>Password:</label><br>
                <input type="password" name="password" value="password123" required>
            </p>
            <p>
                <button type="submit">Test Login</button>
            </p>
        </form>
    </div>

    @if(Auth::check())
        <div class="debug-info">
            <h3>Logout</h3>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    @endif

    <div class="debug-info">
        <h3>Navigation</h3>
        <p><a href="{{ route('home') }}">Go to Homepage</a></p>
        <p><a href="{{ route('profile.index') }}">Go to Profile</a></p>
    </div>
</body>
</html>
