<?php

namespace Database\Seeders;

use App\Models\User;  // Correct namespace
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('app.admin_email', 'miansuleman602@gmail.com');

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Aa.12345'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Admin role
        $admin->syncRoles(['Admin']); // ensures no duplicate roles
    }
}
