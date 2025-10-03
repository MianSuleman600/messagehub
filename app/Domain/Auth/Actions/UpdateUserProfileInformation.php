<?php

namespace App\Domain\Auth\Actions;

use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Update the user's profile information.
     */
    public function update($user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:strict', 'max:255', 'unique:users,email,'.$user->id],
        ])->validate();

        $user->forceFill([
            'name' => $input['name'],
            'email' => strtolower($input['email']),
        ])->save();
    }
}
