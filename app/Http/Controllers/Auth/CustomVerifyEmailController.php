<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmailController extends Controller
{
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        // 1. Manually validate the signed URL signature and hash
        if (! URL::hasValidSignature($request)) {
            abort(403, 'This verification link has expired or is invalid.');
        }

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        // 2. Automatically log them in so they aren't blocked by a login wall
        if (! Auth::check() || Auth::id() !== $user->id) {
            Auth::login($user);
        }

        // 3. Mark as verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(config('fortify.home') . '?verified=1')
                ->with('status', 'Your email is already verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(config('fortify.home') . '?verified=1')
            ->with('status', 'Email verified successfully!');
    }
}