<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CampusShare — @yield('title', 'Resource Sharing')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('map.index') }}" class="text-blue-700 font-bold text-xl">🎓 CampusShare</a>
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('map.index') }}" class="text-gray-600 hover:text-blue-600">📍 Pickup Map</a>
            </div>
            <div class="flex items-center gap-3 text-sm">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Register</a>
                @else
                    <span class="text-gray-500">👤 {{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="text-red-500 hover:text-red-700 font-medium">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
        <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <main class="flex-1">@yield('content')</main>

    <footer class="bg-white border-t mt-12 py-5 text-center text-xs text-gray-400">
        CampusShare © {{ date('Y') }} — CSE471 Spring 2026 | Group 11 | Hrithik
    </footer>
</body>
</html>
