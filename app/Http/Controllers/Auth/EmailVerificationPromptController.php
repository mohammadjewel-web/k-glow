<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            if ($user->hasRole('admin') || $user->hasRole('manager')) {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended(route('customer.dashboard', absolute: false));
            }
        }
        
        return view('auth.verify-email');
    }
}
