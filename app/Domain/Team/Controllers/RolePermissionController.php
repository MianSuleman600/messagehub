<?php
namespace App\Domain\Team\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\Rule;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::where('guard_name', 'web')->orderBy('name')->get();
        $users = User::with('roles')->orderBy('name')->orderBy('email')->get();

        return view('settings.roles', compact('roles', 'permissions', 'users'));
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:50', 'unique:roles,name'],
        ]);

        Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        return back()->with('status', 'Role created successfully.');
    }

    public function destroyRole(Role $role)
    {
        if (strtolower($role->name) === 'admin') {
            return back()->withErrors(['role' => 'The Admin role cannot be deleted.']);
        }

        $role->delete();

        return back()->with('status', 'Role deleted successfully.');
    }

    public function syncRolePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', Rule::exists('permissions', 'id')],
        ]);

        $permissions = Permission::whereIn('id', $validated['permissions'] ?? [])
                                 ->pluck('name')
                                 ->toArray();

        DB::transaction(fn() => $role->syncPermissions($permissions));

        return back()->with('status', "Permissions updated for role {$role->name}.");
    }

    public function syncUserRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['integer', Rule::exists('roles', 'id')],
        ]);

        $roles = Role::whereIn('id', $validated['roles'] ?? [])
                     ->pluck('name')
                     ->toArray();

        DB::transaction(fn() => $user->syncRoles($roles));

        $userName = $user->name ?? $user->email;
        return back()->with('status', "Roles updated for user {$userName}.");
    }
}
