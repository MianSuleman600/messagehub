<?php

namespace App\Domain\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Create a new user instance.
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:strict,dns,spoof', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['accepted'],
            'role' => ['sometimes', 'in:Admin,Staff'], // allow optional role assignment
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => strtolower($input['email']),
            'password' => Hash::make($input['password']),
        ]);

        // Assign role
        $roleName = $input['role'] ?? 'Staff';
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $user->assignRole($role);

        return $user;
    }
}
