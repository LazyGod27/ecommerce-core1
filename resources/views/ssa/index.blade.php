<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('ssa/style.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>iMarket PH</title>
    <style>
        /* Enhanced Modern Animations and Styles */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(44, 60, 140, 0.3); }
            50% { box-shadow: 0 0 30px rgba(44, 60, 140, 0.6); }
        }
        
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: calc(200px + 100%) 0; }
        }
        
        /* Enhanced Hero Section */
        .hero {
            position: relative;
            min-height: 100vh;
            height: auto;
            overflow: visible;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 0 80px 0;
        }

        /* Main content container */
        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 800px;
            width: 100%;
            padding: 40px 20px;
            margin: 0 auto;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* "WELCOME TO" */
        .hero-welcome {
            margin-bottom: 15px;
            animation: fadeInUp 0.8s ease-out 0.1s both;
        }

        .hero-welcome h2 {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8), 0 0 20px rgba(0, 0, 0, 0.5);
            background: rgba(0, 0, 0, 0.3);
            padding: 8px 16px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
            display: inline-block;
        }

        /* "IMARKET PH" main title */
        .hero-content h1 {
            color: #ffffff;
            font-size: 4rem;
            font-weight: 900;
            margin: 10px auto 10px auto;
            text-shadow: 3px 3px 12px rgba(0, 0, 0, 0.9), 0 0 30px rgba(0, 0, 0, 0.7);
            animation: fadeInUp 0.8s ease-out 0.6s both;
            background: rgba(0, 0, 0, 0.4);
            padding: 20px 30px;
            border-radius: 15px;
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            display: block;
            position: relative;
            z-index: 5;
        }

        /* Hero tagline */
        .hero-tagline {
            margin: 15px 0 20px 0;
            animation: fadeInUp 0.8s ease-out 0.8s both;
        }

        .hero-tagline p {
            color: #ffffff;
            font-size: 1.2rem;
            font-weight: 500;
            text-align: center;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8), 0 0 20px rgba(0, 0, 0, 0.5);
            background: rgba(0, 0, 0, 0.3);
            padding: 12px 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: inline-block;
            margin: 0;
        }

        
        .hero-btn {
            animation: float 3s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .hero-content h1 {
                font-size: 3rem;
                padding: 15px 25px;
            }

            .hero-welcome h2 {
                font-size: 1.2rem;
                padding: 6px 12px;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 40px 0 60px 0;
            }

            .hero-content h1 {
                font-size: 2.3rem;
                padding: 12px 20px;
            }

            .hero-welcome h2 {
                font-size: 1rem;
            }

            .hero-tagline p {
                font-size: 1rem;
                padding: 10px 16px;
            }
        }

        @media (max-width: 480px) {
            .hero-content h1 {
                font-size: 1.8rem;
            }

            .hero-welcome h2 {
                font-size: 0.9rem;
            }

            .hero-tagline p {
                font-size: 0.9rem;
                padding: 8px 12px;
            }
        }
        
        .hero-btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        /* Enhanced Product Cards */
        .product-card {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .product-card:hover::before {
            left: 100%;
        }
        
        .product-card:nth-child(1) { animation-delay: 0.1s; }
        .product-card:nth-child(2) { animation-delay: 0.2s; }
        .product-card:nth-child(3) { animation-delay: 0.3s; }
        .product-card:nth-child(4) { animation-delay: 0.4s; }
        .product-card:nth-child(5) { animation-delay: 0.5s; }
        .product-card:nth-child(6) { animation-delay: 0.6s; }
        .product-card:nth-child(7) { animation-delay: 0.7s; }
        .product-card:nth-child(8) { animation-delay: 0.8s; }
        .product-card:nth-child(9) { animation-delay: 0.9s; }
        .product-card:nth-child(10) { animation-delay: 1.0s; }
        
        .product-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .product-img {
            position: relative;
            overflow: hidden;
        }
        
        .product-img img {
            transition: transform 0.4s ease;
        }
        
        .product-card:hover .product-img img {
            transform: scale(1.1);
        }
        
        .discount {
            animation: pulse 2s infinite;
        }
        
        /* Enhanced Section Headers */
        .middle-text {
            animation: fadeInDown 0.8s ease-out;
        }
        
        .middle-text h2 {
            position: relative;
            display: inline-block;
        }
        
        .middle-text h2::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: width 0.5s ease;
        }
        
        .middle-text:hover h2::after {
            width: 100%;
        }
        
        /* Enhanced Buttons */
        .btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* Enhanced Voice and Image Scan FABs */
        .image-scan-fab {
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            z-index: 999;
            transition: all 0.3s ease;
            animation: float 3s ease-in-out infinite;
        }
        
        .image-scan-fab:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
        }
        
        .voice-toggle-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 200px;
            height: 60px;
            border-radius: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: all 0.3s ease;
            animation: float 3s ease-in-out infinite 1s;
        }
        
        .voice-toggle-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
        }
        
        .voice-chat-container {
            position: fixed;
            bottom: -100%;
            right: 20px;
            width: 380px;
            height: 500px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            z-index: 1001;
            transition: bottom 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .voice-chat-container.open {
            bottom: 100px;
        }
        
        .voice-chat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .voice-chat-close-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            line-height: 1;
            transition: transform 0.3s ease;
        }
        
        .voice-chat-close-btn:hover {
            transform: scale(1.2);
        }
        
        .voice-chat-body {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .voice-message {
            padding: 15px 20px;
            border-radius: 20px;
            max-width: 80%;
            word-wrap: break-word;
            animation: fadeInUp 0.3s ease-out;
        }
        
        .voice-bot-message {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            align-self: flex-start;
            border-bottom-left-radius: 5px;
        }
        
        .voice-user-message {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
        }
        
        .voice-chat-input-area {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            border-top: 1px solid #e2e8f0;
            background: white;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }
        
        #voice-mic-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        #voice-mic-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        #voice-mic-btn.listening {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Hero Background Images */
        .hero-slides {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        
        .hero-slides img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        
        .hero-slides img.active {
            opacity: 1;
        }
        
        
        
        
        .hero-stats {
            display: flex;
            gap: 40px;
            margin: 20px 0;
            justify-content: center;
            animation: fadeInUp 0.8s ease-out 1.1s both;
            flex-direction: row;
        }
        
        .stat-item {
            text-align: center;
            color: #ffffff;
            background: rgba(0, 0, 0, 0.4);
            padding: 20px;
            border-radius: 15px;
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .stat-number {
            display: block;
            font-size: 2.5rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 5px;
            color: #ffffff;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8), 0 0 20px rgba(0, 0, 0, 0.5);
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #ffffff;
            font-weight: 600;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);
        }
        
        .shop-now-container {
            margin: 20px 0;
            animation: fadeInUp 0.8s ease-out 1.2s both;
        }
        
        .hero-actions {
            margin: 20px 0 0 0;
            animation: fadeInUp 0.8s ease-out 1.3s both;
        }
        
        .hero-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin: 0 10px;
        }
        
        .hero-btn.primary {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.5), 0 0 30px rgba(255, 107, 107, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero-btn.secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }
        
        .hero-btn.primary:hover {
            box-shadow: 0 12px 35px rgba(255, 107, 107, 0.4);
        }
        
        .hero-scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            color: #2c3c8c;
            font-size: 0.9rem;
            animation: fadeInUp 0.8s ease-out 1.4s both;
        }
        
        .scroll-arrow {
            width: 40px;
            height: 40px;
            border: 2px solid rgba(44, 60, 140, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: float 2s ease-in-out infinite;
        }
        
        .scroll-arrow i {
            font-size: 1.2rem;
        }
        
        /* Enhanced Hero Dots */
        .hero-dots {
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            z-index: 2;
        }
        
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(44, 60, 140, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .dot.active {
            background: #2c3c8c;
            transform: scale(1.2);
            box-shadow: 0 0 20px rgba(44, 60, 140, 0.5);
        }
        
        .dot:hover {
            background: rgba(44, 60, 140, 0.7);
            transform: scale(1.1);
        }
        
        /* Features Section Styles */
        .features-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            position: relative;
            overflow: hidden;
        }
        
        .features-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23e2e8f0" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 60px;
            position: relative;
            z-index: 1;
        }
        
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }
        
        .feature-card:nth-child(1) { animation-delay: 0.1s; }
        .feature-card:nth-child(2) { animation-delay: 0.2s; }
        .feature-card:nth-child(3) { animation-delay: 0.3s; }
        .feature-card:nth-child(4) { animation-delay: 0.4s; }
        .feature-card:nth-child(5) { animation-delay: 0.5s; }
        .feature-card:nth-child(6) { animation-delay: 0.6s; }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover::before {
            transform: scaleX(1);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 15px;
        }
        
        .feature-card p {
            color: #64748b;
            line-height: 1.6;
            font-size: 1rem;
        }
        
        /* Container for features */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
            
            /* Mobile Hero Adjustments */
            .hero-welcome h2 {
                font-size: 1.2rem;
            }
            
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
                max-width: 100%;
                padding: 12px 20px;
            }
            
            .hero {
                padding: 60px 0 100px 0;
            }
            
            .hero-content {
                padding: 20px 10px;
            }
            
            .hero-actions {
                flex-direction: column;
                gap: 15px;
                max-width: 100%;
                padding: 0 10px;
                margin: 15px auto 0 auto;
            }
            
            .hero-btn {
                width: 100%;
                max-width: 280px;
                justify-content: center;
                flex: none;
            }
            
            .shop-now-container {
                margin: 15px auto;
                max-width: 280px;
            }
            
            .hero-stats {
                flex-direction: row;
                gap: 20px;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .hero-actions {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            
            .hero-btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }
        
        /* User Dropdown Styles */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .user-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .user-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .user-name {
            font-size: 14px;
            font-weight: 500;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        
        .user-dropdown:hover .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .user-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .user-dropdown-menu a:last-child {
            border-bottom: none;
        }
        
        .user-dropdown-menu a:hover {
            background-color: #f8f9fa;
        }
        
        .user-dropdown-menu a i {
            width: 16px;
            text-align: center;
        }
        
        /* Product Card Clickable Styles */
        .product-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .product-card:active {
            transform: translateY(-2px);
        }
        
        /* Error and Success Message Styles */
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
        
        .error-message ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .error-message li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('ssa/logo.png') }}" alt="IMARKET PH Logo">
            </a>
        </div>
        <ul class="navbar" id="navbar">
            <li><a href="#" class="active"><i class="ri-home-line"></i> Home</a></li>
            <li><a href="#"><i class="ri-store-line"></i> Mall</a></li>
            <li><a href="#"><i class="ri-percent-line"></i> Flash Deals</a></li>
            <li class="dropdown">
                <a href="#"><i class="ri-list-unordered"></i> Categories <i class="ri-arrow-down-s-line"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('categories.best') }}"><i class="ri-fire-line"></i> Best Selling</a></li>
                    <li><a href="{{ route('categories.new') }}"><i class="ri-star-smile-line"></i> New Arrivals</a></li>
                    <li><a href="{{ route('categories.electronics') }}"><i class="ri-computer-line"></i> Electronics</a></li>
                    <li><a href="{{ route('categories.fashion') }}"><i class="ri-t-shirt-line"></i> Fashion & Apparel</a></li>
                    <li><a href="{{ route('categories.home') }}"><i class="ri-home-4-line"></i> Home & Living</a></li>
                    <li><a href="{{ route('categories.beauty') }}"><i class="ri-heart-line"></i> Beauty & Health</a></li>
                    <li><a href="{{ route('categories.sports') }}"><i class="ri-football-line"></i> Sports & Outdoor</a></li>
                    <li><a href="{{ route('categories.toys') }}"><i class="ri-gamepad-line"></i> Toys & Games</a></li>
                    <li><a href="{{ route('categories.groceries') }}"><i class="ri-shopping-basket-line"></i> Groceries</a></li>
                </ul>
            </li>
        </ul>
        @include('components.search-bar')
        <div class="icons">
            <a href="#" onclick="goToCart()"><i class="ri-shopping-cart-line"></i></a>
            @auth
                <div class="user-dropdown">
                    <a href="{{ route('profile.index') }}" class="user-link">
                        <i class="ri-user-line"></i>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </a>
                    <div class="user-dropdown-menu">
                        <a href="{{ route('profile.index') }}"><i class="ri-user-line"></i> My Profile</a>
                        <a href="{{ route('profile.orders') }}"><i class="ri-shopping-bag-line"></i> My Orders</a>
                        <a href="{{ route('track-order') }}"><i class="ri-truck-line"></i> Track Orders</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ri-logout-box-line"></i> Logout</a>
                    </div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}"><i class="ri-user-line"></i></a>
            @endauth
            <div class="bx bx-menu" id="menu-icon"></div>
        </div>
    </header>


    <section class="hero">
        <div class="hero-slides">
            <img class="slide active" src="{{ asset('ssa/clothes.webp') }}" alt="Slide 1">
            <img class="slide" src="{{ asset('ssa/soap.jpg') }}" alt="Slide 2">
            <img class="slide" src="{{ asset('ssa/pc.webp') }}" alt="Slide 3">
            <img class="slide" src="{{ asset('ssa/home.jpg') }}" alt="Slide 4">
            <img class="slide" src="{{ asset('ssa/school.webp') }}" alt="Slide 5">
        </div>
        <div class="hero-content">
            <div class="hero-welcome">
                <h2>Welcome to</h2>
            </div>
            <h1>IMARKET PH</h1>
            <div class="hero-tagline">
                <p>Your Ultimate Shopping Destination in the Philippines</p>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">50K+</span>
                    <span class="stat-label">Products</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Support</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">99%</span>
                    <span class="stat-label">Satisfaction</span>
                </div>
            </div>
            <div class="shop-now-container">
                <a href="{{ route('products') }}" class="hero-btn primary">
                    <i class="ri-shopping-bag-line"></i>
                    Shop Now
                </a>
            </div>
            <div class="hero-actions">
                <a href="{{ route('categories.best') }}" class="hero-btn secondary">
                    <i class="ri-fire-line"></i>
                    Best Sellers
                </a>
            </div>
        </div>
        <div class="hero-dots">
            <span class="dot active" data-index="0"></span>
            <span class="dot" data-index="1"></span>
            <span class="dot" data-index="2"></span>
            <span class="dot" data-index="3"></span>
            <span class="dot" data-index="4"></span>
        </div>
        <div class="hero-scroll-indicator">
            <div class="scroll-arrow">
                <i class="ri-arrow-down-line"></i>
            </div>
            <span>Scroll to explore</span>
        </div>
    </section>

    <section class="feature">
        <div class="middle-text">
            <h2>Discover <span>Quality Products</span></h2>
        </div>
        <div class="feature-content">
            <div class="product-card" onclick="viewProduct('Men\'s Sneakers', 899, '{{ asset('ssa/sneakers.webp') }}', 'High-quality men\'s sneakers perfect for daily wear and sports activities.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/sneakers.webp') }}" alt="Men's Sneakers">
                    <span class="discount">-35%</span>
                </div>
                <div class="product-info">
                    <h6>Men's Sneakers</h6>
                    <h3>₱899 <del>₱1,399</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 1,245 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Men\'s Sneakers', 899, '{{ asset('ssa/sneakers.webp') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Men\'s Sneakers', 899, '{{ asset('ssa/sneakers.webp') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Gaming Headset', 499, '{{ asset('ssa/headset.jpg') }}', 'Professional gaming headset with surround sound and noise cancellation.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/headset.jpg') }}" alt="Gaming Headset">
                    <span class="discount">-50%</span>
                </div>
                <div class="product-info">
                    <h6>Gaming Headset</h6>
                    <h3>₱499 <del>₱999</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 980 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Gaming Headset', 499, '{{ asset('ssa/headset.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Gaming Headset', 499, '{{ asset('ssa/headset.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Casual Backpack', 599, '{{ asset('ssa/back.jpg') }}', 'Durable and stylish backpack perfect for daily use and travel.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/back.jpg') }}" alt="Casual Backpack">
                    <span class="discount">-40%</span>
                </div>
                <div class="product-info">
                    <h6>Casual Backpack</h6>
                    <h3>₱599 <del>₱999</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 740 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Casual Backpack', 599, '{{ asset('ssa/back.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Casual Backpack', 599, '{{ asset('ssa/back.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Smart Watch', 799, '{{ asset('ssa/relo.jpg') }}', 'Advanced smartwatch with health monitoring and fitness tracking features.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/relo.jpg') }}" alt="Smart Watch">
                    <span class="discount">-60%</span>
                </div>
                <div class="product-info">
                    <h6>Smart Watch</h6>
                    <h3>₱799 <del>₱1,999</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 1,560 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Smart Watch', 799, '{{ asset('ssa/relo.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Smart Watch', 799, '{{ asset('ssa/relo.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('LED Desk Lamp', 299, '{{ asset('ssa/lamp.jpg') }}', 'Adjustable LED desk lamp with multiple brightness levels and USB charging port.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/lamp.jpg') }}" alt="LED Desk Lamp">
                    <span class="discount">-25%</span>
                </div>
                <div class="product-info">
                    <h6>LED Desk Lamp</h6>
                    <h3>₱299 <del>₱399</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 430 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('LED Desk Lamp', 299, '{{ asset('ssa/lamp.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('LED Desk Lamp', 299, '{{ asset('ssa/lamp.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Wireless Earbuds', 699, '{{ asset('ssa/earbuds.jpg') }}', 'High-quality wireless earbuds with noise cancellation and long battery life.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/earbuds.jpg') }}" alt="Wireless Earbuds">
                    <span class="discount">-30%</span>
                </div>
                <div class="product-info">
                    <h6>Wireless Earbuds</h6>
                    <h3>₱699 <del>₱999</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 860 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Wireless Earbuds', 699, '{{ asset('ssa/earbuds.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Wireless Earbuds', 699, '{{ asset('ssa/earbuds.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Gaming Mouse', 329, '{{ asset('ssa/mouse.jpg') }}', 'Precision gaming mouse with customizable RGB lighting and high DPI sensor.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/mouse.jpg') }}" alt="Gaming Mouse">
                    <span class="discount">-45%</span>
                </div>
                <div class="product-info">
                    <h6>Gaming Mouse</h6>
                    <h3>₱329 <del>₱599</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 1,120 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Gaming Mouse', 329, '{{ asset('ssa/mouse.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Gaming Mouse', 329, '{{ asset('ssa/mouse.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Hooded Jacket', 1299, '{{ asset('ssa/hoodie.jpg') }}', 'Comfortable and stylish hooded jacket perfect for casual wear and outdoor activities.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/hoodie.jpg') }}" alt="Hooded Jacket">
                    <span class="discount">-35%</span>
                </div>
                <div class="product-info">
                    <h6>Hooded Jacket</h6>
                    <h3>₱1,299 <del>₱1,999</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 560 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Hooded Jacket', 1299, '{{ asset('ssa/hoodie.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Hooded Jacket', 1299, '{{ asset('ssa/hoodie.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Mechanical Keyboard', 1499, '{{ asset('ssa/keyboard.jpg') }}', 'Professional mechanical keyboard with RGB backlighting and tactile switches.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/keyboard.jpg') }}" alt="Mechanical Keyboard">
                    <span class="discount">-55%</span>
                </div>
                <div class="product-info">
                    <h6>Mechanical Keyboard</h6>
                    <h3>₱1,499 <del>₱3,299</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 2,010 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Mechanical Keyboard', 1499, '{{ asset('ssa/keyboard.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Mechanical Keyboard', 1499, '{{ asset('ssa/keyboard.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Insulated Water Bottle', 399, '{{ asset('ssa/water.jpg') }}', 'High-quality insulated water bottle that keeps drinks cold for 24 hours and hot for 12 hours.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/water.jpg') }}" alt="Insulated Water Bottle">
                    <span class="discount">-20%</span>
                </div>
                <div class="product-info">
                    <h6>Insulated Water Bottle</h6>
                    <h3>₱399 <del>₱499</del></h3>
                    <p class="buyers"><i class="fas fa-user-check"></i> 740 sold</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Insulated Water Bottle', 399, '{{ asset('ssa/water.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Insulated Water Bottle', 399, '{{ asset('ssa/water.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="middle-text">
                <h2>Why Choose <span>iMarket PH</span></h2>
                <p>Experience the best online shopping with our premium features</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ri-truck-line"></i>
                    </div>
                    <h3>Fast Delivery</h3>
                    <p>Get your orders delivered within 24-48 hours across the Philippines with our reliable shipping partners.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ri-shield-check-line"></i>
                    </div>
                    <h3>Secure Payments</h3>
                    <p>Shop with confidence using our secure payment gateway supporting all major payment methods.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ri-customer-service-2-line"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated customer service team is available round the clock to assist you with any queries.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ri-refresh-line"></i>
                    </div>
                    <h3>Easy Returns</h3>
                    <p>Not satisfied? Return your items within 30 days with our hassle-free return policy.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ri-price-tag-3-line"></i>
                    </div>
                    <h3>Best Prices</h3>
                    <p>Find the best deals and competitive prices on thousands of products from trusted sellers.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="ri-award-line"></i>
                    </div>
                    <h3>Quality Guarantee</h3>
                    <p>All products are verified for quality and authenticity before being listed on our platform.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="product">
        <div class="middle-text">
            <h2>New <span>Arrival</span></h2>
        </div>
        <div class="feature-content">
            <div class="product-card" onclick="viewProduct('AirPods Pro 2', 8999, '{{ asset('ssa/airpods.jpg') }}', 'Latest Apple AirPods Pro with active noise cancellation and spatial audio.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/airpods.jpg') }}" alt="AirPods Pro 2">
                    <span class="discount">NEW</span>
                </div>
                <div class="product-info">
                    <h6>Apple</h6>
                    <h3>AirPods Pro 2</h3>
                    <p class="buyers"><i class="fas fa-bolt"></i> Just Arrived</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('AirPods Pro 2', 8999, '{{ asset('ssa/airpods.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('AirPods Pro 2', 8999, '{{ asset('ssa/airpods.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Nike Dunk Low Panda', 5999, '{{ asset('ssa/shoes.jpg') }}', 'Classic Nike Dunk Low in black and white colorway, perfect for street style.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/shoes.jpg') }}" alt="Nike Dunk Low">
                    <span class="discount">NEW</span>
                </div>
                <div class="product-info">
                    <h6>Nike</h6>
                    <h3>Dunk Low Panda</h3>
                    <p class="buyers"><i class="fas fa-bolt"></i> Fresh Drop</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Nike Dunk Low Panda', 5999, '{{ asset('ssa/shoes.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Nike Dunk Low Panda', 5999, '{{ asset('ssa/shoes.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Sony DualSense Edge', 7999, '{{ asset('ssa/controller.jpg') }}', 'Professional gaming controller for PS5 with customizable buttons and triggers.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/controller.jpg') }}" alt="PS5 DualSense Edge">
                    <span class="discount">NEW</span>
                </div>
                <div class="product-info">
                    <h6>Sony</h6>
                    <h3>DualSense Edge</h3>
                    <p class="buyers"><i class="fas fa-bolt"></i> Latest Release</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Sony DualSense Edge', 7999, '{{ asset('ssa/controller.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Sony DualSense Edge', 7999, '{{ asset('ssa/controller.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('Smart Watch Ultra Gen 2', 12999, '{{ asset('ssa/ultra.jpg') }}', 'Advanced smartwatch with health monitoring, GPS, and extended battery life.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/ultra.jpg') }}" alt="Smart Watch Ultra">
                    <span class="discount">NEW</span>
                </div>
                <div class="product-info">
                    <h6>Smart Tech</h6>
                    <h3>Watch Ultra Gen 2</h3>
                    <p class="buyers"><i class="fas fa-bolt"></i> Brand New</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Smart Watch Ultra Gen 2', 12999, '{{ asset('ssa/ultra.jpg') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Smart Watch Ultra Gen 2', 12999, '{{ asset('ssa/ultra.jpg') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
            <div class="product-card" onclick="viewProduct('JBL Go 4 Portable Speaker', 1999, '{{ asset('ssa/jbl.png') }}', 'Compact portable speaker with powerful sound and waterproof design.')">
                <div class="product-img">
                    <img src="{{ asset('ssa/jbl.png') }}" alt="JBL Go 4 Speaker">
                    <span class="discount">NEW</span>
                </div>
                <div class="product-info">
                    <h6>JBL</h6>
                    <h3>Go 4 Portable Speaker</h3>
                    <p class="buyers"><i class="fas fa-bolt"></i> New Arrival</p>
                    <div class="actions">
                        <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('JBL Go 4 Portable Speaker', 1999, '{{ asset('ssa/jbl.png') }}')">Buy Now</a>
                        <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('JBL Go 4 Portable Speaker', 1999, '{{ asset('ssa/jbl.png') }}')"><i class="fas fa-shopping-cart"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="voice-chat-container" id="voice-chat-container">
        <div class="voice-chat-header">
            <span class="voice-chat-title">Voice Command AI</span>
            <button class="voice-chat-close-btn" id="voice-close-chat-btn">&times;</button>
        </div>
        <div class="voice-chat-body" id="voice-chat-body">
            <div class="voice-message voice-bot-message">Hello! Tap the microphone to start talking.</div>
        </div>
        <div class="voice-chat-input-area">
            <button id="voice-mic-btn"><i class="ri-mic-fill"></i></button>
        </div>
    </div>

    <button class="voice-toggle-btn" id="voice-toggle-btn">Voice Command AI</button>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h5>Customer Care</h5>
                <ul>
                    <li><a href="{{ route('customer-service') }}">Customer Service</a></li>
                    <li><a href="#">How to Buy</a></li>
                    <li><a href="#">Shipping & Delivery</a></li>
                    <li><a href="#">Return & Refund</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h5>About ImarketPH</h5>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h5>Payment Methods</h5>
                <div class="footer-logos">
                    <img src="{{ asset('ssa/visa.png') }}" alt="Visa">
                    <img src="{{ asset('ssa/mastercard.png') }}" alt="Mastercard">
                    <img src="{{ asset('ssa/gcash.png') }}" alt="GCash">
                    <img src="{{ asset('ssa/maya.png') }}" alt="Paymaya">
                </div>
            </div>
            <div class="footer-section">
                <h5>Delivery Services</h5>
                <div class="footer-logos">
                    <img src="{{ asset('ssa/jnt.png') }}" alt="J&T Express">
                    <img src="{{ asset('ssa/ninjavan.jpg') }}" alt="Ninja Van">
                    <img src="{{ asset('ssa/lbc.png') }}" alt="LBC Express">
                    <img src="{{ asset('ssa/flash.png') }}" alt="Flash Express">
                </div>
            </div>
            <div class="footer-section">
                <h5>Follow Us</h5>
                <div class="footer-socials">
                    <a href="#"><img src="{{ asset('ssa/facebook.png') }}" alt="Facebook"></a>
                    <a href="#"><img src="{{ asset('ssa/instagram.jpg') }}" alt="Instagram"></a>
                    <a href="#"><img src="{{ asset('ssa/twitter.png') }}" alt="Twitter"></a>
                    <a href="#"><img src="{{ asset('ssa/youtube.png') }}" alt="YouTube"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 All Rights Reserved by ImarketPH</p>
        </div>
    </footer>

    <button class="image-scan-fab" onclick="window.open('https://images.google.com/', '_blank')" title="Image Scan">
        <i class="ri-camera-line"></i>
    </button>

    <form id="add-to-cart-form" method="POST" style="display:none;"></form>
    <script src="{{ asset('js/cart-auth.js') }}"></script>
    <script>

        function viewProduct(productName, price, image, description) {
            // Create and show product detail modal
            const modal = document.createElement('div');
            modal.className = 'product-modal';
            modal.innerHTML = `
                <div class="product-modal-content">
                    <div class="product-modal-header">
                        <h3>${productName}</h3>
                        <button class="product-modal-close" onclick="closeProductModal()">&times;</button>
                    </div>
                    <div class="product-modal-body">
                        <div class="product-modal-image">
                            <img src="${image}" alt="${productName}">
                        </div>
                        <div class="product-modal-info">
                            <h4>₱${price.toLocaleString()}</h4>
                            <p class="product-description">${description}</p>
                            
                            <!-- Size Selection -->
                            <div class="product-options">
                                <div class="size-selector">
                                    <label>Size:</label>
                                    <div class="size-options">
                                        <input type="radio" id="size-s" name="size" value="S" checked>
                                        <label for="size-s" class="size-option">S</label>
                                        <input type="radio" id="size-m" name="size" value="M">
                                        <label for="size-m" class="size-option">M</label>
                                        <input type="radio" id="size-l" name="size" value="L">
                                        <label for="size-l" class="size-option">L</label>
                                        <input type="radio" id="size-xl" name="size" value="XL">
                                        <label for="size-xl" class="size-option">XL</label>
                                        <input type="radio" id="size-xxl" name="size" value="XXL">
                                        <label for="size-xxl" class="size-option">XXL</label>
                                    </div>
                                </div>
                                
                                <!-- Quantity Selector -->
                                <div class="quantity-selector">
                                    <label>Quantity:</label>
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn" onclick="decreaseQuantity()">-</button>
                                        <input type="number" id="product-quantity" value="1" min="1" max="10" class="quantity-input">
                                        <button type="button" class="quantity-btn" onclick="increaseQuantity()">+</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="product-modal-actions">
                                <button class="btn buy" onclick="buyNowWithOptions('${productName}', ${price}, '${image}'); closeProductModal();">Buy Now</button>
                                <button class="btn cart" onclick="addToCartWithOptions('${productName}', ${price}, '${image}'); closeProductModal();">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Add modal styles
            const style = document.createElement('style');
            style.textContent = `
                .product-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.8);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 10000;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }
                .product-modal.show {
                    opacity: 1;
                }
                .product-modal-content {
                    background: white;
                    border-radius: 12px;
                    max-width: 600px;
                    width: 90%;
                    max-height: 90vh;
                    overflow-y: auto;
                    transform: scale(0.8);
                    transition: transform 0.3s ease;
                    display: flex;
                    flex-direction: column;
                }
                .product-modal.show .product-modal-content {
                    transform: scale(1);
                }
                .product-modal-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 20px;
                    border-bottom: 1px solid #eee;
                }
                .product-modal-header h3 {
                    margin: 0;
                    color: #333;
                }
                .product-modal-close {
                    background: none;
                    border: none;
                    font-size: 24px;
                    cursor: pointer;
                    color: #999;
                }
                .product-modal-close:hover {
                    color: #333;
                }
                .product-modal-body {
                    display: flex;
                    padding: 20px;
                    gap: 20px;
                    flex: 1;
                    overflow-y: auto;
                }
                .product-modal-image {
                    flex: 1;
                }
                .product-modal-image img {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                    border-radius: 8px;
                }
                .product-modal-info {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }
                .product-modal-info h4 {
                    font-size: 24px;
                    color: #e74c3c;
                    margin: 0 0 15px 0;
                }
                .product-description {
                    color: #666;
                    line-height: 1.6;
                    margin-bottom: 20px;
                }
                .product-options {
                    margin-bottom: 20px;
                    flex: 1;
                }
                .size-selector, .quantity-selector {
                    margin-bottom: 15px;
                }
                .size-selector label, .quantity-selector label {
                    display: block;
                    font-weight: 600;
                    color: #333;
                    margin-bottom: 8px;
                }
                .size-options {
                    display: flex;
                    gap: 8px;
                    flex-wrap: wrap;
                }
                .size-options input[type="radio"] {
                    display: none;
                }
                .size-option {
                    display: inline-block;
                    padding: 8px 16px;
                    border: 2px solid #ddd;
                    border-radius: 6px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    background: white;
                    font-weight: 500;
                }
                .size-option:hover {
                    border-color: #e74c3c;
                    color: #e74c3c;
                }
        .size-options input[type="radio"]:checked + .size-option {
            background: #e74c3c;
            color: white;
            border-color: #e74c3c;
        }
        
        
        .search-btn i {
            font-size: 18px;
        }
        
                .quantity-controls {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }
                .quantity-btn {
                    width: 32px;
                    height: 32px;
                    border: 2px solid #ddd;
                    background: white;
                    border-radius: 6px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    font-size: 16px;
                    transition: all 0.3s ease;
                }
                .quantity-btn:hover {
                    border-color: #e74c3c;
                    color: #e74c3c;
                }
                .quantity-input {
                    width: 60px;
                    height: 32px;
                    text-align: center;
                    border: 2px solid #ddd;
                    border-radius: 6px;
                    font-weight: 600;
                }
                .quantity-input:focus {
                    outline: none;
                    border-color: #e74c3c;
                }
                .product-modal-actions {
                    display: flex;
                    gap: 10px;
                    margin-top: auto;
                    padding-top: 20px;
                    flex-shrink: 0;
                }
                .product-modal-actions .btn {
                    padding: 12px 20px;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    flex: 1;
                    text-align: center;
                    white-space: nowrap;
                    font-size: 14px;
                }
                .product-modal-actions .btn.buy {
                    background: #e74c3c;
                    color: white;
                }
                .product-modal-actions .btn.buy:hover {
                    background: #c0392b;
                }
                .product-modal-actions .btn.cart {
                    background: #3498db;
                    color: white;
                }
                .product-modal-actions .btn.cart:hover {
                    background: #2980b9;
                }
                @media (max-width: 768px) {
                    .product-modal-body {
                        flex-direction: column;
                        padding: 15px;
                    }
                    .product-modal-actions {
                        flex-direction: column;
                        gap: 8px;
                    }
                    .product-modal-actions .btn {
                        padding: 14px 20px;
                        font-size: 16px;
                    }
                    .product-modal-content {
                        max-height: 95vh;
                        width: 95%;
                    }
                }
            `;
            
            document.head.appendChild(style);
            document.body.appendChild(modal);
            
            // Show modal with animation
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
            
            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeProductModal();
                }
            });
        }

        function closeProductModal() {
            const modal = document.querySelector('.product-modal');
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.remove();
                }, 300);
            }
        }

        function increaseQuantity() {
            const quantityInput = document.getElementById('product-quantity');
            if (quantityInput) {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue < 10) {
                    quantityInput.value = currentValue + 1;
                }
            }
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('product-quantity');
            if (quantityInput) {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            }
        }

        function getSelectedSize() {
            const sizeInputs = document.querySelectorAll('input[name="size"]');
            for (let input of sizeInputs) {
                if (input.checked) {
                    return input.value;
                }
            }
            return 'S'; // Default size
        }

        function getSelectedQuantity() {
            const quantityInput = document.getElementById('product-quantity');
            return quantityInput ? parseInt(quantityInput.value) : 1;
        }

        function addToCartWithOptions(productName, price, image) {
            const size = getSelectedSize();
            const quantity = getSelectedQuantity();
            
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const form = document.getElementById('add-to-cart-form');
            const syntheticId = Date.now();
            form.setAttribute('action', `${'{{ url('/') }}'}/cart/add/${syntheticId}`);
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrf}">
                <input type="hidden" name="product_name" value="${productName} (Size: ${size})">
                <input type="hidden" name="product_price" value="${price}">
                <input type="hidden" name="product_image" value="${image}">
                <input type="hidden" name="quantity" value="${quantity}">
            `;
            form.submit();
        }

        // buyNow and buyNowWithOptions functions are now loaded from cart-auth.js

        const voiceToggleBtn=document.getElementById('voice-toggle-btn');
        const voiceChatContainer=document.getElementById('voice-chat-container');
        const voiceCloseChatBtn=document.getElementById('voice-close-chat-btn');
        const voiceMicBtn=document.getElementById('voice-mic-btn');
        const voiceChatBody=document.getElementById('voice-chat-body');
        if(voiceToggleBtn&&voiceChatContainer&&voiceCloseChatBtn){
            voiceToggleBtn.addEventListener('click',()=>{voiceChatContainer.classList.toggle('open')});
            voiceCloseChatBtn.addEventListener('click',()=>{voiceChatContainer.classList.remove('open')});
        }
        if('SpeechRecognition'in window||'webkitSpeechRecognition'in window){
            const SpeechRecognition=window.SpeechRecognition||window.webkitSpeechRecognition;const recognition=new SpeechRecognition();
            recognition.continuous=false;recognition.interimResults=false;recognition.lang='en-US';
            voiceMicBtn.addEventListener('click',()=>{voiceMicBtn.classList.add('listening');recognition.start()});
            recognition.onresult=(event)=>{const transcript=event.results[0][0].transcript;const userMessageDiv=document.createElement('div');userMessageDiv.className='voice-message voice-user-message';userMessageDiv.textContent=transcript;voiceChatBody.appendChild(userMessageDiv);voiceChatBody.scrollTop=voiceChatBody.scrollHeight;setTimeout(()=>{const botResponseDiv=document.createElement('div');botResponseDiv.className='voice-message voice-bot-message';botResponseDiv.textContent=`You said: "${transcript}". This is a simulated AI response.`;voiceChatBody.appendChild(botResponseDiv);voiceChatBody.scrollTop=voiceChatBody.scrollHeight},1000)};
            recognition.onend=()=>{voiceMicBtn.classList.remove('listening')};
            recognition.onerror=(event)=>{console.error('Speech recognition error:',event.error);voiceMicBtn.classList.remove('listening');const errorDiv=document.createElement('div');errorDiv.className='voice-message voice-bot-message';errorDiv.textContent='Sorry, there was an error with voice recognition. Please try again.';voiceChatBody.appendChild(errorDiv)};
        }else{voiceMicBtn.disabled=true;voiceMicBtn.textContent='Not Supported';voiceMicBtn.title='Your browser does not support the Web Speech API.';const fallbackMessage=document.createElement('div');fallbackMessage.className='voice-message voice-bot-message';fallbackMessage.textContent='Voice commands are not supported on this browser. Please use a modern browser like Chrome or Edge.';voiceChatBody.appendChild(fallbackMessage)}
        window.onload=()=>{startHeroSlider&&startHeroSlider()};
        document.addEventListener('DOMContentLoaded',()=>{
            // Modal functionality removed - using dedicated HTML pages instead
            
            
            // Smooth scroll for hero scroll indicator
            const scrollIndicator = document.querySelector('.hero-scroll-indicator');
            if (scrollIndicator) {
                scrollIndicator.addEventListener('click', function() {
                    const featuresSection = document.querySelector('.features-section');
                    if (featuresSection) {
                        featuresSection.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            }
            
            // Intersection Observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, observerOptions);
            
            // Observe elements for scroll animations
            document.querySelectorAll('.feature-card, .product-card, .middle-text').forEach(el => {
                observer.observe(el);
            });
            
            // Hero slides rotation
            const slides = document.querySelectorAll('.hero-slides .slide');
            let currentSlide = 0;
            
            function rotateSlides() {
                slides.forEach(slide => slide.classList.remove('active'));
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('active');
            }
            
            // Rotate slides every 5 seconds
            setInterval(rotateSlides, 5000);
        });
        
    </script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="{{ asset('ssa/script.js') }}"></script>
    <script src="{{ asset('js/logo-consistency.js') }}?v={{ time() }}"></script>
</body>
</html>


