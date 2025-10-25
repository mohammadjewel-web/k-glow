<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use App\Notifications\OtpVerificationNotification;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Show verification method selection page
     */
    public function showVerificationMethodPage()
    {
        $user = Auth::user();
        
        if ($user->is_verified) {
            return redirect()->route('customer.dashboard');
        }

        return view('auth.select-verification-method', compact('user'));
    }

    /**
     * Show OTP verification page
     */
    public function showVerificationPage($type)
    {
        if (!in_array($type, ['email', 'sms'])) {
            abort(404);
        }

        $user = Auth::user();

        if ($user->is_verified) {
            return redirect()->route('customer.dashboard');
        }

        return view('auth.verify-otp', compact('type', 'user'));
    }

    /**
     * Send OTP based on verification method
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'method' => 'required|in:email,sms,both',
        ]);

        $user = Auth::user();
        $method = $request->method;

        try {
            if ($method === 'email' || $method === 'both') {
                $this->sendEmailOTP($user);
            }

            if ($method === 'sms' || $method === 'both') {
                $this->sendSmsOTP($user);
            }

            // Update user verification method
            $user->update(['verification_method' => $method]);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your ' . ($method === 'both' ? 'email and phone' : $method),
            ]);
        } catch (\Exception $e) {
            Log::error('OTP sending failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
            ], 500);
        }
    }

    /**
     * Resend OTP
     */
    public function resendOTP(Request $request)
    {
        $request->validate([
            'type' => 'required|in:email,sms',
        ]);

        $user = Auth::user();
        $type = $request->type;

        try {
            if ($type === 'email') {
                $this->sendEmailOTP($user);
            } else {
                $this->sendSmsOTP($user);
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP resent successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('OTP resend failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.',
            ], 500);
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
            'type' => 'required|in:email,sms',
        ]);

        $user = Auth::user();
        $otp = $request->otp;
        $type = $request->type;
        $identifier = $type === 'email' ? $user->email : $user->phone;

        // Find valid OTP
        $otpRecord = OtpVerification::valid($identifier, $type)->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.',
            ], 400);
        }

        // Check max attempts
        if ($otpRecord->maxAttemptsExceeded()) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum attempts exceeded. Please request a new OTP.',
            ], 400);
        }

        // Verify OTP
        if ($otpRecord->otp !== $otp) {
            $otpRecord->incrementAttempts();
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.',
                'attempts_left' => 5 - $otpRecord->attempts,
            ], 400);
        }

        // Mark OTP as verified
        $otpRecord->markAsVerified();

        // Mark user as verified based on type
        if ($type === 'email') {
            $user->markEmailAsVerified();
        } else {
            $user->markPhoneAsVerified();
        }

        // Check if user needs to verify both
        $needsBothVerification = $user->verification_method === 'both';
        $isFullyVerified = $user->is_verified;

        if ($needsBothVerification && !$isFullyVerified) {
            $nextVerificationType = $type === 'email' ? 'sms' : 'email';
            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' verified successfully. Please verify your ' . $nextVerificationType . '.',
                'next_step' => $nextVerificationType,
                'fully_verified' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification successful! Your account is now active.',
            'fully_verified' => true,
            'redirect' => route('customer.dashboard'),
        ]);
    }

    /**
     * Send Email OTP
     */
    protected function sendEmailOTP(User $user)
    {
        // Invalidate previous OTPs
        OtpVerification::where('identifier', $user->email)
            ->where('type', 'email')
            ->where('is_verified', false)
            ->delete();

        // Generate new OTP
        $otp = OtpVerification::generateOTP();

        // Create OTP record
        OtpVerification::create([
            'user_id' => $user->id,
            'identifier' => $user->email,
            'otp' => $otp,
            'type' => 'email',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send email notification
        $user->notify(new OtpVerificationNotification($otp));

        Log::info('Email OTP sent to: ' . $user->email, ['otp' => $otp]);
    }

    /**
     * Send SMS OTP
     */
    protected function sendSmsOTP(User $user)
    {
        // Invalidate previous OTPs
        OtpVerification::where('identifier', $user->phone)
            ->where('type', 'sms')
            ->where('is_verified', false)
            ->delete();

        // Generate new OTP
        $otp = OtpVerification::generateOTP();

        // Create OTP record
        OtpVerification::create([
            'user_id' => $user->id,
            'identifier' => $user->phone,
            'otp' => $otp,
            'type' => 'sms',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send SMS
        $this->smsService->sendOTP($user->phone, $otp);

        Log::info('SMS OTP sent to: ' . $user->phone, ['otp' => $otp]);
    }

    /**
     * Skip verification (optional - for testing)
     */
    public function skipVerification()
    {
        $user = Auth::user();
        
        // Mark user as verified
        $user->update([
            'is_verified' => true,
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Verification skipped successfully!');
    }
}
