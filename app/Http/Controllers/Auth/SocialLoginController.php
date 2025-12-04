<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

     public function callback($provider)
    {
        
        try {
        $socialUser = Socialite::driver($provider)->stateless()->user();
        //$socialUser = Socialite::driver($provider)->user();

        // Find or create the user
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            ['name' => $socialUser->getName() ?? $socialUser->getNickname(), 
             'password' => bcrypt(str()->random(16)),
             'avatar' => $socialUser->getAvatar(),     // store avatar
             'provider' => $provider,
             'provider_id' => $socialUser->getId(),
             ] // random password
        );

        // Assign default role
        if ($user->wasRecentlyCreated) {
            $user->assignRole('user'); // or 'super-admin' for first user
        }

        if (is_null($user->email_verified_at)) {
            $user->markEmailAsVerified();
           }

        Auth::login($user, true);

        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
        }else {
        return redirect()->route('home');
      }

    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Google login failed. Please try again.');
    }
    
        
    }
}
