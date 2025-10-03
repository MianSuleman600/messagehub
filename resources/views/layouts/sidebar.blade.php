<aside class="w-64 bg-gray-900 border-r border-gray-800 backdrop-blur-lg flex-shrink-0">
    <nav class="flex flex-col p-4 space-y-2">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('dashboard') ? 'bg-green-900' : '' }}">
            <x-heroicon-o-home class="w-5 h-5" />
            <span>Dashboard</span>
        </a>

        {{-- Inbox --}}
        <a href="{{ route('inbox.index') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('inbox.*') ? 'bg-green-900' : '' }}">
            <x-heroicon-o-inbox class="w-5 h-5" />
            <span>Inbox</span>
        </a>

        {{-- Contacts --}}
        <a href="{{ route('contacts.index') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('contacts.*') ? 'bg-green-900' : '' }}">
            <x-heroicon-o-users class="w-5 h-5" />
            <span>Contacts</span>
        </a>

        {{-- Reports (Admin only) --}}
        @role('Admin')
            <a href="{{ route('reports.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('reports.*') ? 'bg-green-900' : '' }}">
                <x-heroicon-o-chart-bar class="w-5 h-5" />
                <span>Reports</span>
            </a>
        @endrole

        {{-- Roles --}}
        <a href="{{ url('/settings/roles') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
            <x-heroicon-o-shield-check class="w-5 h-5" />
            <span>Roles</span>
        </a>

        {{-- Channels --}}
        <a href="{{ url('/settings/channels') }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-800 transition">
            <x-heroicon-o-device-tablet class="w-5 h-5" />
            <span>Channels</span>
        </a>
    </nav>
</aside>
