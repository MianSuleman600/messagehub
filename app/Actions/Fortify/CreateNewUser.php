<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Dev-friendly: skip 'spoof' validation if Intl not available
        $emailRules = ['required', 'string', 'email', 'max:255', Rule::unique(User::class)];

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => $emailRules,
            'password' => $this->passwordRules(),
            'terms' => ['required', 'accepted'],
            'role' => ['sometimes', 'in:Admin,Staff'], // optional role
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => strtolower($input['email']),
            'password' => Hash::make($input['password']),
        ]);

        // Assign role: default to 'Staff'
        $roleName = $input['role'] ?? 'Staff';
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $user->assignRole($role);

        return $user;
    }
}
