<aside class="w-full h-full flex flex-col">
    <nav class="flex-1 overflow-y-auto px-3 py-5">
        <div class="px-3 mb-4">
            <p class="text-xs uppercase tracking-wider text-gray-500">Navigation</p>
        </div>

        {{-- Item helper: base styles --}}
        @php
            $baseItem = 'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors';
            $iconBase = 'w-5 h-5';
            function activeClasses($isActive) {
                return $isActive
                    ? 'bg-gradient-to-r from-green-900/40 to-green-900/10 text-white ring-1 ring-inset ring-green-700/40'
                    : 'text-gray-300 hover:text-white hover:bg-gray-800/60';
            }
        @endphp

        {{-- Dashboard --}}
        @php $isActive = request()->routeIs('dashboard'); @endphp
        <a href="{{ route('dashboard') }}"
           class="{{ $baseItem }} {{ activeClasses($isActive) }}"
           @if($isActive) aria-current="page" @endif>
            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-full {{ $isActive ? 'bg-green-500' : 'bg-transparent group-hover:bg-gray-700' }}"></span>
            <x-heroicon-o-home class="{{ $iconBase }} {{ $isActive ? 'text-green-400' : 'text-gray-400 group-hover:text-gray-200' }}" />
            <span class="font-medium">Dashboard</span>
        </a>

        {{-- Inbox --}}
        @php $isActive = request()->routeIs('inbox.*'); @endphp
        <a href="{{ route('inbox.index') }}"
           class="{{ $baseItem }} {{ activeClasses($isActive) }}"
           @if($isActive) aria-current="page" @endif>
            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-full {{ $isActive ? 'bg-green-500' : 'bg-transparent group-hover:bg-gray-700' }}"></span>
            <x-heroicon-o-inbox class="{{ $iconBase }} {{ $isActive ? 'text-green-400' : 'text-gray-400 group-hover:text-gray-200' }}" />
            <span class="font-medium">Inbox</span>
        </a>

        {{-- Contacts --}}
        @php $isActive = request()->routeIs('contacts.*'); @endphp
        <a href="{{ route('contacts.index') }}"
           class="{{ $baseItem }} {{ activeClasses($isActive) }}"
           @if($isActive) aria-current="page" @endif>
            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-full {{ $isActive ? 'bg-green-500' : 'bg-transparent group-hover:bg-gray-700' }}"></span>
            <x-heroicon-o-users class="{{ $iconBase }} {{ $isActive ? 'text-green-400' : 'text-gray-400 group-hover:text-gray-200' }}" />
            <span class="font-medium">Contacts</span>
        </a>

        {{-- Reports (Admin only) --}}
        @role('Admin')
            @php $isActive = request()->routeIs('reports.*'); @endphp
            <a href="{{ route('reports.index') }}"
               class="{{ $baseItem }} {{ activeClasses($isActive) }}"
               @if($isActive) aria-current="page" @endif>
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-full {{ $isActive ? 'bg-green-500' : 'bg-transparent group-hover:bg-gray-700' }}"></span>
                <x-heroicon-o-chart-bar class="{{ $iconBase }} {{ $isActive ? 'text-green-400' : 'text-gray-400 group-hover:text-gray-200' }}" />
                <span class="font-medium">Reports</span>
            </a>
        @endrole

        <div class="px-3 mt-6 mb-2">
            <p class="text-xs uppercase tracking-wider text-gray-500">Settings</p>
        </div>

        {{-- Roles --}}
        <a href="{{ url('/settings/roles') }}"
           class="{{ $baseItem }} text-gray-300 hover:text-white hover:bg-gray-800/60">
            <x-heroicon-o-shield-check class="{{ $iconBase }} text-gray-400 group-hover:text-gray-200" />
            <span class="font-medium">Roles</span>
        </a>

        {{-- Channels --}}
        @php $isActive = request()->is('settings/channels*'); @endphp
        <a href="{{ url('/settings/channels') }}"
           class="{{ $baseItem }} {{ activeClasses($isActive) }}"
           @if($isActive) aria-current="page" @endif>
            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-full {{ $isActive ? 'bg-green-500' : 'bg-transparent group-hover:bg-gray-700' }}"></span>
            <x-heroicon-o-device-tablet class="{{ $iconBase }} {{ $isActive ? 'text-green-400' : 'text-gray-400 group-hover:text-gray-200' }}" />
            <span class="font-medium">Channels</span>
        </a>
    </nav>

    {{-- Sidebar bottom note --}}
    <div class="border-t border-gray-800/70 px-4 py-3 text-xs text-gray-500">
        <span class="text-gray-400">Ottica Erremme</span> • <span>Secure by design</span>
    </div>
</aside>