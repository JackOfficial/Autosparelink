<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\LoginResponseController;
use Laravel\Fortify\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       $this->app->singleton(LoginResponse::class, LoginResponseController::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Fortify::authenticateUsing(function (Request $request) {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // If user signed up with a social provider
            if ($user->provider) {
                // Stop here and return a validation error
                throw ValidationException::withMessages([
                 'email' => 'These credentials do not match our records.',
                 'socialLoginInError' => 'It looks like you signed up with Google. Please click login with google button.'
                ]);
            }
            
            // If normal account, check password
            if ($user->password && Hash::check($request->password, $user->password)) {
                return $user;
            }
            }

            // Normal login (check password)
            if (Hash::check($request->password, $user->password)) {
                return $user;
            }

            // Default failure message
        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
// fallback: Fortify shows "These credentials do not match our records."
         });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function () {
        return view('auth.login');
        });

        Fortify::registerView(function () {
        return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
        return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function (Request $request) {
        return view('auth.reset-password', ['request' => $request]);
        });

        Fortify::verifyEmailView(function () {
        return view('auth.verify-email');
        });

        Fortify::confirmPasswordView(function () {
        return view('auth.confirm-password');
        });


    }
}
