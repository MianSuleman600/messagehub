<?php

use Laravel\Fortify\Features;

return [

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    */
    'model' => App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Guard & Password Broker
    |--------------------------------------------------------------------------
    */
    'guard' => 'web',
    'passwords' => 'users',

    /*
    |--------------------------------------------------------------------------
    | Username / Email
    |--------------------------------------------------------------------------
    */
    'username' => 'email',
    'email' => 'email',

    /*
    |--------------------------------------------------------------------------
    | Views & Home
    |--------------------------------------------------------------------------
    */
    'views' => true, // Blade views enabled
    'home' => '/dashboard',

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    */
    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]),
    ],

    /*
    |--------------------------------------------------------------------------
    | Optional Customizations
    |--------------------------------------------------------------------------
    */
    'redirects' => [
        'login' => '/dashboard',
        'logout' => '/',
        'two-factor' => '/two-factor-challenge',
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Rules
    |--------------------------------------------------------------------------
    */
    'password_rules' => [
        'min:8',
        'letters',
        'mixedCase',
        'numbers',
        'symbols',
    ],

    /*
    |--------------------------------------------------------------------------
    | Views (Optional: customize Fortify views)
    |--------------------------------------------------------------------------
    */
    'views_paths' => [
        'login' => 'auth.login',
        'register' => 'auth.register',
        'verify' => 'auth.verify-email',
        'forgot-password' => 'auth.forgot-password',
        'reset-password' => 'auth.reset-password',
        'two-factor-challenge' => 'auth.two-factor-challenge',
    ],
];
