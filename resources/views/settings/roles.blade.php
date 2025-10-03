{{-- resources/views/settings/roles.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8 text-gray-100">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Roles & Permissions</h1>
    </div>

    {{-- Status / Errors --}}
    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-900/40 text-green-100 px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-900/40 text-red-100 px-4 py-3">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Create Role --}}
        <div class="bg-gray-900/60 border border-gray-800 rounded-xl p-5">
            <h2 class="text-lg font-medium mb-4">Create Role</h2>
            <form method="POST" action="{{ route('settings.roles.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="role-name" class="block text-sm mb-1">Name</label>
                    <input id="role-name" name="name" type="text" required
                           value="{{ old('name') }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-700"
                           placeholder="e.g. Supervisor" />
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 bg-green-700 hover:bg-green-600 rounded-lg">Create</button>
                </div>
            </form>
        </div>

        {{-- Roles -> Permissions --}}
        <div class="lg:col-span-2 bg-gray-900/60 border border-gray-800 rounded-xl p-5">
            <h2 class="text-lg font-medium mb-4">Assign Permissions to Roles</h2>

            <div class="space-y-6">
                @forelse ($roles as $role)
                    <div class="border border-gray-800 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold">{{ $role->name }}</h3>
                            <form method="POST" action="{{ route('settings.roles.destroy', $role) }}"
                                  onsubmit="return confirm('Delete role {{ $role->name }}?');">
                                @csrf
                                @method('DELETE')
                                @if (strtolower($role->name) !== 'admin')
                                    <button type="submit" class="text-red-300 hover:text-red-200 text-sm">Delete</button>
                                @else
                                    <span class="text-xs text-gray-400">Protected</span>
                                @endif
                            </form>
                        </div>

                        <form method="POST" action="{{ route('settings.roles.permissions.sync', $role) }}">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($permissions as $perm)
                                    <label class="flex items-center gap-2 text-sm bg-gray-800/70 border border-gray-700 rounded-md px-3 py-2 cursor-pointer">
                                        <input type="checkbox" name="permissions[]"
                                               value="{{ $perm->id }}"
                                               {{ $role->permissions->contains('id', $perm->id) ? 'checked' : '' }}
                                               class="rounded border-gray-600 bg-gray-700">
                                        <span>{{ $perm->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="px-3 py-1.5 bg-green-700 hover:bg-green-600 rounded-md text-sm">Save</button>
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
    <div class="mt-8 bg-gray-900/60 border border-gray-800 rounded-xl p-5">
        <h2 class="text-lg font-medium mb-4">Assign Roles to Users</h2>

        <div class="space-y-4">
            @forelse ($users as $user)
                <form method="POST" action="{{ route('settings.users.roles.sync', $user) }}"
                      class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-gray-900/40 border border-gray-800 rounded-lg px-4 py-3">
                    @csrf
                    <div>
                        <div class="font-medium">{{ $user->name ?? $user->email }}</div>
                        <div class="text-xs text-gray-400">{{ $user->email }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <select name="roles[]" multiple
                                class="min-w-[220px] bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 focus:outline-none"
                                size="3">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-2 bg-green-700 hover:bg-green-600 rounded-lg text-sm">Save</button>
                    </div>
                </form>
            @empty
                <p class="text-sm text-gray-400">No users found.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
