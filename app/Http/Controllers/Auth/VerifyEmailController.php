<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            return $this->redirectBasedOnRole($user, '?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->redirectBasedOnRole($user, '?verified=1');
    }
    
    private function redirectBasedOnRole($user, $query = '')
    {
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return redirect()->intended('/admin/dashboard' . $query);
        } else {
            return redirect()->intended(route('customer.dashboard', absolute: false) . $query);
        }
    }
}
