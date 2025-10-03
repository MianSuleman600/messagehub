<?php

namespace App\Domain\Auth\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    /**
     * Reset the user's password.
     */
    public function reset($user, array $input): void
    {
        Validator::make($input, [
            'password' => ['required', 'confirmed', Password::defaults()],
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
