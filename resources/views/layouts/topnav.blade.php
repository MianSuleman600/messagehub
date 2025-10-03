<header class="sticky top-0 z-50 bg-gray-900 border-b border-gray-800 backdrop-blur-lg shadow-sm">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-4 sm:px-6 lg:px-8 h-16">
        {{-- Left: Logo & Role --}}
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-white hover:text-green-400 transition">
                Multi-Channel Suite
            </a>
            @auth
                <span class="hidden sm:inline-block px-2 py-1 rounded-full bg-gray-800 text-green-400 text-xs font-semibold">
                    @role('Admin') Admin @elserole('Staff') Staff @endrole
                </span>
            @endauth
        </div>

        {{-- Right: Search & Actions --}}
        <div class="flex items-center space-x-4">
            {{-- Inbox search --}}
            <form method="GET" action="{{ route('inbox.index') }}" class="flex items-center space-x-2">
                <input 
                    type="search" 
                    name="q" 
                    placeholder="Search inbox..." 
                    value="{{ request('q') }}" 
                    class="px-3 py-1 rounded-lg bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400"
                />
                <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-400 transition">Go</button>
            </form>

            @auth
                <a href="{{ route('settings.security') }}" class="text-gray-400 hover:text-white transition">Security</a>
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-500 transition">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition">Login</a>
                <a href="{{ route('register') }}" class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-400 transition">Register</a>
            @endauth
        </div>
    </div>
</header>
