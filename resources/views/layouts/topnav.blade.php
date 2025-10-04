<div class="max-w-7xl mx-auto h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
    {{-- Left: Logo + Role --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="text-lg sm:text-xl font-semibold tracking-tight text-white hover:text-green-400 transition-colors">
           Ottica Erremme
        </a>
        @auth
            <span class="hidden sm:inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-gray-800/80 border border-gray-700 text-green-400 text-xs font-semibold">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500"></span>
                @role('Admin') Admin @elserole('Staff') Staff @endrole
            </span>
        @endauth
    </div>

    {{-- Right: Search & Actions --}}
    <div class="flex items-center gap-3 sm:gap-4">
        {{-- Inbox search --}}
        <form method="GET" action="{{ route('inbox.index') }}" class="hidden sm:flex items-center gap-2">
            <div class="relative">
                <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-500" />
                <input
                    type="search"
                    name="q"
                    placeholder="Search inbox..."
                    value="{{ request('q') }}"
                    class="pl-8 pr-3 py-2 rounded-lg bg-gray-900/80 border border-gray-800 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:border-green-600 w-64"
                />
            </div>
            <button type="submit" class="px-3 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg font-medium transition-colors">
                Go
            </button>
        </form>

        @auth
            <a href="{{ route('settings.security') }}" class="text-gray-400 hover:text-white transition-colors text-sm">
                Security
            </a>
            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg text-sm font-medium transition-colors">
                    Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors text-sm">
                Login
            </a>
            <a href="{{ route('register') }}" class="px-3 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg text-sm font-medium transition-colors">
                Register
            </a>
        @endauth
    </div>
</div>