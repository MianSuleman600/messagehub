@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto text-gray-100">
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-semibold tracking-tight text-white">Roles & Permissions</h1>
            <p class="text-sm text-gray-400 mt-1">Create roles, assign permissions, and map roles to users</p>
        </div>
    </div>

    {{-- Status / Errors --}}
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-green-800/50 bg-green-900/20 text-green-100 px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-800/50 bg-red-900/30 text-red-100 px-4 py-3">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Create Role --}}
        <div class="bg-gray-950/60 border border-gray-800 rounded-2xl p-6 shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Create Role</h2>
            <form method="POST" action="{{ route('settings.roles.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="role-name" class="block text-sm mb-1 text-gray-300">Name</label>
                    <input id="role-name" name="name" type="text" required
                           value="{{ old('name') }}"
                           class="w-full bg-gray-900/80 border border-gray-800 rounded-lg px-3 py-2 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:border-green-600"
                           placeholder="e.g. Supervisor" />
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-500 rounded-lg text-white font-semibold transition-colors">
                        Create
                    </button>
                </div>
            </form>
        </div>

        {{-- Roles -> Permissions --}}
        <div class="lg:col-span-2 bg-gray-950/60 border border-gray-800 rounded-2xl p-6 shadow-lg">
            <h2 class="text-xl font-semibold mb-5">Assign Permissions to Roles</h2>

            <div class="space-y-6">
                @forelse ($roles as $role)
                    <div class="border border-gray-800 rounded-xl p-5 bg-gray-900/50">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-white">{{ $role->name }}</h3>
                            <form method="POST" action="{{ route('settings.roles.destroy', $role) }}"
                                  onsubmit="return confirm('Delete role {{ $role->name }}?');">
                                @csrf
                                @method('DELETE')
                                @if (strtolower($role->name) !== 'admin')
                                    <button type="submit" class="px-3 py-1.5 rounded-md text-sm bg-red-700/80 hover:bg-red-600 text-white transition-colors">
                                        Delete
                                    </button>
                                @else
                                    <span class="text-xs px-2 py-1 rounded-md bg-gray-800/80 border border-gray-700 text-gray-400">Protected</span>
                                @endif
                            </form>
                        </div>

                        <form method="POST" action="{{ route('settings.roles.permissions.sync', $role) }}">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($permissions as $perm)
                                    <label class="flex items-center gap-2 text-sm bg-gray-900/70 border border-gray-800 rounded-md px-3 py-2 cursor-pointer hover:bg-gray-900/80 transition-colors">
                                        <input type="checkbox" name="permissions[]"
                                               value="{{ $perm->id }}"
                                               {{ $role->permissions->contains('id', $perm->id) ? 'checked' : '' }}
                                               class="rounded border-gray-600 bg-gray-800 text-green-500 focus:ring-2 focus:ring-green-500/40">
                                        <span class="text-gray-200">{{ $perm->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white font-semibold transition-colors">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">No roles yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Users -> Roles --}}
    <div class="mt-8 bg-gray-950/60 border border-gray-800 rounded-2xl p-6 shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Assign Roles to Users</h2>

        <div class="space-y-4">
            @forelse ($users as $user)
                <form method="POST" action="{{ route('settings.users.roles.sync', $user) }}"
                      class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-gray-900/50 border border-gray-800 rounded-xl px-4 py-3">
                    @csrf
                    <div class="min-w-0">
                        <div class="font-medium text-white truncate">{{ $user->name ?? $user->email }}</div>
                        <div class="text-xs text-gray-400 truncate">{{ $user->email }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <select name="roles[]" multiple
                                class="min-w-[240px] bg-gray-900/80 border border-gray-800 rounded-lg px-3 py-2 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600"
                                size="3">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-500 rounded-lg text-white text-sm font-semibold transition-colors">
                            Save
                        </button>
                    </div>
                </form>
            @empty
                <p class="text-sm text-gray-400">No users found.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection