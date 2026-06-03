<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Community Meter')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
        .lang-en, .lang-es { transition: opacity 0.15s ease; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">
    @yield('content')
</body>
</html>
