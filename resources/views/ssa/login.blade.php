<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login & Account Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-bg': '#94dcf4',
                        'card-bg': '#bdccdc',
                        'text-dark': '#353c61',
                        'text-muted': '#2c3c8c',
                        'accent-color': '#4bc5ec',
                        'border-light': '#5c8c9c',
                        'input-bg': '#ffffff',
                        'btn-hover': '#2c3c8c',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes slideInFromTop {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-card {
            animation: slideInFromTop 0.3s ease-out forwards;
        }
        
        /* Custom styles not available in Tailwind */
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            color: #2c3c8c;
        }

        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #000000;
        }

        .separator:not(:empty)::before {
            margin-right: .75em;
        }

        .separator:not(:empty)::after {
            margin-left: .75em;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4" style="background-color: #94dcf4;">

    <div class="flex flex-col md:flex-row bg-card-bg rounded-3xl shadow-2xl w-full max-w-4xl mx-auto border border-border-light overflow-hidden animate-[fadeInScale_0.6s_ease-out_forwards]">

        <div class="relative flex-1 flex flex-col items-center justify-center p-8 text-card-bg rounded-b-3xl md:rounded-r-none md:rounded-l-3xl">
            <img src="{{ asset('ssa/lgo.png') }}" alt="App Logo" class="absolute inset-0 w-full h-full object-cover rounded-b-3xl md:rounded-r-none md:rounded-l-3xl">
        
            <div class="absolute inset-0 bg-text-dark opacity-60 rounded-b-3xl md:rounded-r-none md:rounded-l-3xl"></div>

            <div class="relative z-10 flex flex-col items-center justify-center text-center">
                <span class="text-5xl font-extrabold text-white mb-2">iMarket</span>
                <p  class="text-lg text-gray-200 mt-2 max-w-sm">
                    Your Market, Your Choice
                </p>
            </div>
        </div>

        <div class="flex-1 p-4 sm:p-8 w-full">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-text-dark mb-2">Welcome</h1>
                <p class="text-lg text-text-muted mb-8">Sign in to your account.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-text-dark mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Email" value="{{ old('email') }}"
                           class="w-full px-4 py-3 bg-input-bg border-b border-border-light text-text-dark rounded-t-xl focus:outline-none focus:ring-1 focus:ring-accent-color focus:border-accent-color transition duration-200">
                </div>
                <div class="mb-3">
                    <label for="password" class="block text-sm font-medium text-text-dark mb-2">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Password"
                           class="w-full px-4 py-3 bg-input-bg border-b border-border-light text-text-dark rounded-t-xl focus:outline-none focus:ring-1 focus:ring-accent-color focus:border-accent-color transition duration-200">
                </div>

                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-accent-color bg-input-bg border-border-light rounded focus:ring-accent-color">
                        <label for="remember-me" class="ml-2 block text-sm text-text-muted">Remember me</label>
                    </div>
                    <a href="#" onclick="showForgotPasswordModal(event)" class="text-sm font-medium text-accent-color hover:text-btn-hover transition duration-200">Forgot Password?</a>
                </div>

                <button type="submit" class="w-full py-3 bg-accent-color text-card-bg font-bold rounded-xl shadow-md hover:bg-btn-hover transition duration-200 focus:outline-none focus:ring-2 focus:ring-accent-color focus:ring-offset-2">
                    Log In
                </button>
            </form>

            <div class="separator my-8 text-sm text-text-muted">or continue with</div>
            
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('auth.facebook') }}" class="flex items-center justify-center px-4 py-3 bg-input-bg border border-border-light rounded-xl text-text-dark font-medium hover:bg-border-light transition duration-200">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/facebook.svg" alt="Facebook logo" class="w-5 h-5 mr-3 invert-[.8]">
                    Facebook
                </a>
                <a href="{{ route('auth.google') }}" class="flex items-center justify-center px-4 py-3 bg-input-bg border border-border-light rounded-xl text-text-dark font-medium hover:bg-border-light transition duration-200">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google logo" class="w-5 h-5 mr-3 invert-[.8]">
                    Google
                </a>
            </div>

            <div class="text-center text-sm text-text-muted mt-6">
                Don't have an account? <a href="/ssa/register.html" class="text-accent-color hover:text-btn-hover transition duration-200">Create an account</a>
            </div>
            <div class="text-center text-xs text-text-muted mt-2">
                By logging in, you agree to our <a href="#" onclick="showTermsAndConditions(event)" class="text-accent-color hover:text-btn-hover transition duration-200">Terms & Conditions</a>.
            </div>
        </div>
    </div>

    <!-- Terms & Conditions Modal -->
    <div id="termsModal" class="fixed inset-0 bg-primary-bg bg-opacity-70 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-card-bg rounded-3xl shadow-2xl p-4 sm:p-8 w-full max-w-2xl modal-card border border-border-light">
            <h2 class="text-2xl font-bold text-text-dark mb-4">Terms & Conditions</h2>
            <div class="text-text-muted text-sm overflow-y-auto max-h-[70vh] pr-4">
                <ol>
                    <li>
                        <strong class="text-text-dark">Acceptance of Terms</strong>
                        <p>By using IMARKET, you agree that you have read, understood, and accepted these Terms and Conditions, along with our Privacy Policy. If you do not agree, please do not use our services.</p>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">Eligibility</strong>
                        <ul>
                            <li>Users must be at least 18 years old or have parental/guardian consent to use IMARKET.</li>
                            <li>By registering, you confirm that the information you provide is accurate and up to date.</li>
                        </ul>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">User Account</strong>
                        <ul>
                            <li>You are responsible for maintaining the confidentiality of your login credentials.</li>
                            <li>You agree to notify us immediately of any unauthorized access or use of your account.</li>
                            <li>IMARKET reserves the right to suspend or terminate accounts that violate these Terms.</li>
                        </ul>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">Platform Features</strong>
                        <ul>
                            <li>IMARKET provides innovative e-commerce tools, including but not limited to:</li>
                            <li>AI Image & Voice Search – Allows users to search for products using images or spoken commands.</li>
                            <li>NLP Review-Based Product Discovery – Helps users discover products based on AI-powered analysis of customer reviews.</li>
                            <li>Core Transaction Services – Including browsing, purchasing, payment processing, and order tracking.</li>
                        </ul>
                        <p>IMARKET does not guarantee 100% accuracy of AI-generated results and recommendations.</p>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">User Responsibilities</strong>
                        <ul>
                            <li>By using IMARKET, you agree to:</li>
                            <li>Use the Platform only for lawful purposes.</li>
                            <li>Provide truthful information when purchasing, selling, or reviewing products.</li>
                            <li>Not upload harmful, abusive, fraudulent, or misleading content.</li>
                            <li>Not misuse AI features for activities such as impersonation, fake reviews, or product manipulation.</li>
                        </ul>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">Seller and Buyer Policies</strong>
                        <ul>
                            <li>Sellers must ensure that product listings are accurate, lawful, and do not infringe intellectual property rights.</li>
                            <li>Buyers are responsible for reviewing product details before making a purchase.</li>
                            <li>IMARKET is not liable for disputes between buyers and sellers but may facilitate resolution through support channels.</li>
                        </ul>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">Payments and Transaction</strong>
                        <ul>
                            <li>All payments must be made through authorized payment gateways integrated into IMARKET.</li>
                            <li>IMARKET does not store sensitive financial data such as credit card details.</li>
                            <li>Refunds and cancellations are subject to seller policies and applicable laws.</li>
                        </ul>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">AI Limitations and Disclaimer</strong>
                        <ul>
                            <li>AI-driven features (image/voice search, NLP reviews) are provided to enhance user experience but may not always provide accurate or complete results.</li>
                            <li>IMARKET is not responsible for purchase decisions made solely based on AI recommendations.</li>
                        </ul>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">Intellectual Property</strong>
                        <ul>
                            <li>All content, trademarks, and software related to IMARKET are the property of the company or its licensors.</li>
                            <li>Users retain ownership of content they upload but grant IMARKET a non-exclusive license to display and use such content on the platform.</li>
                        </ul>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">Privacy and Data Usage</strong>
                        <ul>
                            <li>IMARKET collects and processes user data in accordance with its Privacy Policy.</li>
                            <li>Data may be used to improve AI features, personalize recommendations, and enhance platform security.</li>
                        </ul>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">Prohibited Activities</strong>
                        <p>You agree not to:</p>
                        <ul>
                            <li>Engage in fraudulent transactions.</li>
                            <li>Reverse-engineer or tamper with IMARKET's AI systems.</li>
                            <li>Upload viruses, spam, or malicious code.</li>
                            <li>Violate applicable laws, regulations, or third-party rights.</li>
                        </ul>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">Limitations of Liability</strong>
                        <ul>
                            <li>IMARKET provides services "as is" without warranties of any kind.</li>
                            <li>The company shall not be held liable for losses, damages, or disputes arising from use of the platform, AI recommendations, or third-party transactions.</li>
                        </ul>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">Termination of Service</strong>
                        <ul>
                            <li>IMARKET reserves the right to suspend, restrict, or terminate access to accounts that violate these Terms without prior notice.</li>
                        </ul>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">Governing Law</strong>
                        <p>These Terms and Conditions shall be governed by and construed in accordance with the laws of the Philippines.</p>
                    </li>
                    <li class="mt-4">
                        <strong class="text-text-dark">Changes to Terms</strong>
                        <p>IMARKET may update these Terms at any time. Users will be notified of major changes, and continued use of the platform constitutes acceptance of updated Terms.</p>
                    </li>
                </ol>
            </div>
            <button onclick="closeTermsModal()" class="w-full mt-6 py-3 bg-border-light text-text-dark font-medium rounded-xl hover:bg-input-bg transition duration-200">
                Close
            </button>
        </div>
    </div>

    <div id="forgotPasswordModal" class="fixed inset-0 bg-primary-bg bg-opacity-70 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-card-bg rounded-3xl shadow-2xl p-4 sm:p-8 w-full max-w-sm modal-card border border-border-light">
            <h2 class="text-2xl font-bold text-text-dark mb-4">Forgot Password</h2>
            <p class="text-sm text-text-muted mb-6">Enter your email to receive a password reset link.</p>
            <form id="forgotPasswordForm" onsubmit="handleSendResetLink(event)">
                <div class="mb-5">
                    <label for="resetEmail" class="block text-sm font-medium text-text-dark mb-2">Email Address</label>
                    <input type="email" id="resetEmail" name="resetEmail" required placeholder="you@example.com"
                           class="w-full px-4 py-3 bg-input-bg border-b border-border-light text-text-dark rounded-t-xl focus:outline-none focus:ring-1 focus:ring-accent-color focus:border-accent-color transition duration-200">
                </div>
                <button type="submit" class="w-full py-3 bg-accent-color text-card-bg font-bold rounded-xl shadow-md hover:bg-btn-hover transition duration-200 focus:outline-none focus:ring-2 focus:ring-accent-color focus:ring-offset-2">
                    Send Reset Link
                </button>
            </form>
            <button onclick="closeForgotPasswordModal()" class="w-full mt-4 py-3 bg-border-light text-text-dark font-medium rounded-xl hover:bg-input-bg transition duration-200">
                Cancel
            </button>
        </div>
    </div>

    <script>
        function showForgotPasswordModal(event) {
            event.preventDefault();
            document.getElementById('forgotPasswordModal').classList.remove('hidden');
        }

        function closeForgotPasswordModal() {
            document.getElementById('forgotPasswordModal').classList.add('hidden');
        }

        function handleSendResetLink(event) {
            event.preventDefault();
            const resetEmail = document.getElementById('resetEmail').value;
            console.log("Password reset link requested for:", resetEmail);
            closeForgotPasswordModal();
            alert(`A password reset link has been sent to ${resetEmail}. (Static example)`);
        }

        function showTermsAndConditions(event) {
            event.preventDefault();
            document.getElementById('termsModal').classList.remove('hidden');
        }

        function closeTermsModal() {
            document.getElementById('termsModal').classList.add('hidden');
        }
    </script>
</body>
</html>
