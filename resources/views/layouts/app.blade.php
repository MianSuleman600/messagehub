<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? config('app.name', 'Ottica Erremme') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind + Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Custom dark theme tokens --}}
    <style>
        :root {
            --bg:#0b1020;
            --panel:#0f172a;
            --muted:#94a3b8;
            --text:#e2e8f0;
            --accent:#22c55e;
            --accent-2:#2563eb;
            --danger:#ef4444;
        }
    </style>
</head>
<body class="min-h-dvh bg-gray-950 text-gray-100 antialiased selection:bg-green-500/20 selection:text-green-200">
    {{-- Subtle gradient background accents --}}
    <div class="pointer-events-none fixed inset-0 -z-10">
        <div class="absolute -top-28 -left-40 h-96 w-96 rounded-full bg-green-500/10 blur-3xl"></div>
        <div class="absolute -bottom-32 -right-40 h-[28rem] w-[28rem] rounded-full bg-blue-500/10 blur-3xl"></div>
    </div>

    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-50 focus:rounded-md focus:bg-gray-800 focus:px-3 focus:py-2">Skip to content</a>

    <div class="flex min-h-dvh">
        {{-- Sidebar --}}
        @auth
            <aside class="hidden lg:flex lg:w-72 bg-gradient-to-b from-gray-950/80 to-gray-950/30 border-r border-gray-800/70 backdrop-blur supports-[backdrop-filter]:backdrop-blur-xl">
                @include('layouts.sidebar')
            </aside>
        @endauth

        {{-- Main column --}}
        <div class="flex-1 flex flex-col">
            {{-- Topnav --}}
            <header class="sticky top-0 z-50 bg-gray-950/80 border-b border-gray-800/70 backdrop-blur supports-[backdrop-filter]:backdrop-blur-xl">
                @include('layouts.topnav')
            </header>

            {{-- Content --}}
            @php
                $defaultContainerClasses = 'max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8';
                $mainClasses = View::hasSection('fullScreenLayout') ? '' : $defaultContainerClasses;
            @endphp

            <main id="main" class="flex-1 {{ $mainClasses }}">
                {{-- Status message --}}
                @if(session('status'))
                    <div class="mb-6">
                        <div class="rounded-xl border border-green-800/50 bg-green-900/20 text-green-200 px-4 py-3 shadow-sm">
                            <div class="flex items-start gap-3">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-400 mt-0.5" />
                                <div>
                                    <p class="font-semibold tracking-tight">Success</p>
                                    <p class="text-sm">{{ session('status') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Page content --}}
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="mt-auto bg-gray-950/80 border-t border-gray-800/70 text-gray-400 text-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-300">&copy; {{ date('Y') }} Ottica Erremme</span>
                        <span class="hidden sm:inline text-gray-600">•</span>
                        <span class="text-gray-500">All rights reserved.</span>
                    </div>
                    <div class="text-gray-500">
                        <span class="hidden sm:inline">Dark UI •</span>
                        <span>v{{ config('app.version', '1.0') }}</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>