<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Multi-Channel Suite' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind + Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Custom dark theme colors --}}
    <style>
        :root {
            --bg:#0f172a;
            --panel:#0b1221;
            --muted:#94a3b8;
            --text:#e2e8f0;
            --accent:#22c55e;
            --accent-2:#2563eb;
            --danger:#ef4444;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 font-sans flex min-h-screen">

<div class="app flex w-full">
    {{-- Sidebar --}}
    @auth
        <aside class="sidebar w-64 bg-gray-900 border-r border-gray-800 backdrop-blur-lg">
            @include('layouts.sidebar')
        </aside>
    @endauth

    {{-- Main content --}}
    <div class="content flex-1 flex flex-col">
        {{-- Topnav --}}
        <header class="topnav sticky top-0 z-50 bg-gray-900 border-b border-gray-800 backdrop-blur-lg shadow-sm">
            @include('layouts.topnav')
        </header>

        {{-- Main container --}}
        @php
            $defaultContainerClasses = 'max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8';
            $mainClasses = View::hasSection('fullScreenLayout') ? '' : $defaultContainerClasses;
        @endphp
        <main class="{{ $mainClasses }} flex-1">
            {{-- Status message --}}
            @if(session('status'))
                <div class="mb-6">
                    <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded-md shadow-sm" role="alert">
                        <p class="font-bold">Success</p>
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- Page content --}}
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-gray-900 text-gray-500 text-center py-4 border-t border-gray-800 shadow-inner text-sm mt-auto">
            &copy; {{ date('Y') }} Multi-Channel Suite. All rights reserved.
        </footer>
    </div>
</div>
</body>
</html>
