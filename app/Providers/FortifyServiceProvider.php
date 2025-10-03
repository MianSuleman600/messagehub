<?php

namespace App\Providers;

use App\Domain\Auth\Actions\CreateNewUser;
use App\Domain\Auth\Actions\ResetUserPassword;
use App\Domain\Auth\Actions\UpdateUserPassword;
use App\Domain\Auth\Actions\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\ConfirmPasswordViewResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Fortify ConfirmPassword view
        $this->app->bind(ConfirmPasswordViewResponse::class, function () {
            return new class implements ConfirmPasswordViewResponse {
                public function toResponse($request)
                {
                    return view('auth.confirm-password');
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Actions
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Views (Blade)
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn ($request) => view('auth.reset-password', ['request' => $request]));
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));

        // Rate limiters
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return [
                Limit::perMinute(5)->by($email . $request->ip()),  // per-user throttle
                Limit::perMinute(50)->by($request->ip()),           // global IP throttle
            ];
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Optional: Custom authentication logic
        Fortify::authenticateUsing(function (Request $request) {
            $user = \App\Models\User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                // Extra checks: active user or roles
                if ($user->is_active) {
                    return $user;
                }
            }

            return null;
        });
    }
}
