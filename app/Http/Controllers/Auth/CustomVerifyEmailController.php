<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CustomVerifyEmailController extends Controller
{
    public function __invoke(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // 1. Verify the hash matches the user's email
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        // 2. Automatically log the user in so the session matches
        if (! Auth::check() || Auth::id() !== $user->id) {
            Auth::login($user);
        }

        // 3. Mark as verified if not already
        if ($user->hasVerifiedEmail()) {
            return redirect()->config('fortify.home') . '?verified=1';
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->config('fortify.home') . '?verified=1';
    }
}