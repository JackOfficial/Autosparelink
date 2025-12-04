<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponseController implements LoginResponseContract
{
     public function toResponse($request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        elseif($user->hasRole('superadmin')) {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('home');
    }
}
