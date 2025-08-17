<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iMarket - @yield('title')</title>
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous">
</head>
<body class="bg-white font-sans antialiased flex flex-col min-h-screen">
    @include('layouts.header')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Message box -->
    <div id="message-box" class="message-box fixed bg-green-500 text-white px-6 py-3 rounded-md shadow-lg hidden">
        <p id="message-text"></p>
    </div>
</body>
</html>