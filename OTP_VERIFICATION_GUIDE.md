# Customer Registration OTP Verification System

## Overview

This document explains the complete Email and SMS OTP verification system implemented for customer registration in K-Glow e-commerce platform.

## Features Implemented

### ✅ 1. **Dual Verification Methods**

-   **Email OTP**: 6-digit code sent via email
-   **SMS OTP**: 6-digit code sent via SMS
-   **Both**: Enhanced security with both email and SMS verification

### ✅ 2. **Database Schema**

#### Users Table (New Fields)

```sql
- email_verified_at (timestamp, nullable)
- phone_verified_at (timestamp, nullable)
- is_verified (boolean, default: false)
- verification_method (string, nullable) - 'email', 'sms', or 'both'
```

#### OTP Verifications Table

```sql
- id (bigint)
- user_id (foreignId)
- identifier (string) - email or phone number
- otp (string, 6 digits)
- type (enum: 'email', 'sms')
- expires_at (timestamp) - OTP valid for 10 minutes
- is_verified (boolean)
- verified_at (timestamp, nullable)
- attempts (integer) - Max 5 attempts
- timestamps
```

### ✅ 3. **Registration Flow**

1. **User Registration**

    - User fills registration form (name, email, phone, password)
    - Account created with `is_verified = false`
    - User automatically logged in
    - Redirected to verification method selection page

2. **Verification Method Selection**

    - User chooses: Email, SMS, or Both
    - Beautiful UI with card-based selection
    - Shows which methods are already verified
    - Development mode includes "Skip" option

3. **OTP Sending**

    - System generates 6-digit random OTP
    - Stores in database with 10-minute expiry
    - Sends via selected method:
        - **Email**: Laravel notification system
        - **SMS**: Custom SMS service (configurable)
    - OTP logged for development (when SMS not configured)

4. **OTP Verification**

    - Clean 6-box OTP input interface
    - Auto-focus and auto-submit
    - Paste support for easy entry
    - Real-time timer (10 minutes)
    - Max 5 verification attempts
    - Resend option with 60-second cooldown

5. **Post-Verification**
    - Email/SMS verified flags updated
    - `is_verified` set to true
    - User redirected to customer dashboard
    - Full access to customer features

### ✅ 4. **Security Features**

-   **OTP Expiry**: 10 minutes validity
-   **Attempt Limits**: Maximum 5 attempts per OTP
-   **Resend Cooldown**: 60 seconds between resends
-   **Token Invalidation**: Previous OTPs invalidated on new request
-   **CSRF Protection**: All forms protected
-   **Middleware Guard**: Unverified users redirected to verification

### ✅ 5. **User Experience**

-   **Modern UI**: Tailwind CSS with brand colors
-   **Responsive Design**: Mobile-friendly interface
-   **Auto-Focus**: Smooth OTP entry experience
-   **Real-time Feedback**: Error and success messages
-   **Countdown Timers**: Visual countdown for expiry and resend
-   **Auto-Submit**: OTP auto-submits when complete
-   **Paste Support**: Copy-paste full OTP code

### ✅ 6. **SMS Integration**

#### Supported SMS Providers (Bangladesh)

1. **BulkSMS Bangladesh** - https://www.bulksmsbd.com/
2. **SSL Wireless** - https://sslwireless.com/
3. **Grameenphone SMS API**
4. **Robi Axiata SMS API**
5. **Banglalink SMS API**

#### Configuration (.env)

```env
SMS_API_KEY=your_api_key_here
SMS_SENDER_ID=K-GLOW
SMS_API_URL=https://sms-provider-api-url.com/send
```

#### Implementation

The `SmsService` class (`app/Services/SmsService.php`) handles:

-   Phone number formatting (Bangladesh format)
-   SMS API integration
-   OTP message templating
-   Error logging
-   Development mode fallback

**Note**: When SMS API is not configured, OTPs are logged to `storage/logs/laravel.log` for development testing.

### ✅ 7. **Email Notification**

The `OtpVerificationNotification` sends professional emails with:

-   User-friendly greeting
-   Clear OTP display
-   Expiry information
-   Security warning
-   K-Glow branding

### ✅ 8. **Middleware Protection**

`EnsureUserIsVerified` middleware:

-   Applied to all customer routes
-   Checks if user is verified
-   Redirects unverified users to verification page
-   Exempts admin and super-admin roles
-   Shows warning message

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Auth/
│   │       ├── RegisteredUserController.php (Updated)
│   │       └── VerificationController.php (New)
│   └── Middleware/
│       └── EnsureUserIsVerified.php (New)
├── Models/
│   ├── OtpVerification.php (New)
│   └── User.php (Updated)
├── Notifications/
│   └── OtpVerificationNotification.php (New)
└── Services/
    └── SmsService.php (New)

database/migrations/
├── 2025_10_20_170734_add_verification_fields_to_users_table.php
└── 2025_10_20_170813_create_otp_verifications_table.php

resources/views/auth/
├── select-verification-method.blade.php (New)
└── verify-otp.blade.php (New)

routes/
└── web.php (Updated with verification routes)

bootstrap/
└── app.php (Middleware alias registered)
```

## Routes

### Verification Routes (Authenticated)

```php
GET  /verification/method          - Select verification method
GET  /verify/{type}                - OTP input page
POST /verification/send            - Send OTP
POST /verification/resend          - Resend OTP
POST /verification/verify          - Verify OTP
GET  /verification/skip            - Skip verification (debug only)
```

### Customer Routes (Verified)

All routes under `/customer/*` now require verification.

## Testing Guide

### 1. **Development Mode (SMS Not Configured)**

When `SMS_API_KEY` is not set in `.env`:

-   SMS OTPs are logged to `storage/logs/laravel.log`
-   Check logs for OTP codes
-   Email OTPs work normally via mail driver

### 2. **Testing Email Verification**

1. Register new account
2. Select "Email Verification"
3. Check email inbox or use Mailtrap/Log driver
4. Copy 6-digit OTP
5. Enter OTP on verification page
6. Should redirect to dashboard

### 3. **Testing SMS Verification**

1. Register new account
2. Select "SMS Verification"
3. Check `storage/logs/laravel.log` for OTP (if not configured)
4. Or receive SMS if API is configured
5. Enter OTP on verification page
6. Should redirect to dashboard

### 4. **Testing Both Verification**

1. Register new account
2. Select "Both (Recommended)"
3. First, verify email
4. Then, verify SMS
5. Both must be completed for full access

### 5. **Testing Middleware**

1. Register without verifying
2. Try accessing `/customer/dashboard`
3. Should redirect to verification page
4. After verification, access granted

## API Responses

### Send OTP

```json
{
    "success": true,
    "message": "OTP sent successfully to your email"
}
```

### Verify OTP (Success - Fully Verified)

```json
{
    "success": true,
    "message": "Verification successful! Your account is now active.",
    "fully_verified": true,
    "redirect": "/customer/dashboard"
}
```

### Verify OTP (Success - Need SMS)

```json
{
    "success": true,
    "message": "Email verified successfully. Please verify your sms.",
    "next_step": "sms",
    "fully_verified": false
}
```

### Verify OTP (Error)

```json
{
    "success": false,
    "message": "Invalid OTP. Please try again.",
    "attempts_left": 4
}
```

## SMS Provider Integration

### Step 1: Choose Provider

Select an SMS provider from the supported list based on:

-   Pricing
-   Reliability
-   API documentation
-   Bangladesh coverage

### Step 2: Sign Up & Get Credentials

1. Create account with provider
2. Get API credentials:
    - API Key
    - Sender ID
    - API URL

### Step 3: Configure Environment

Add to `.env`:

```env
SMS_API_KEY=your_api_key
SMS_SENDER_ID=K-GLOW
SMS_API_URL=https://provider-url.com/api/send
```

### Step 4: Update SMS Service (if needed)

Modify `app/Services/SmsService.php` `send()` method for provider-specific API:

```php
$response = Http::post($this->apiUrl, [
    'api_key' => $this->apiKey,
    'sender_id' => $this->senderId,
    'phone' => $phone,
    'message' => $message,
    // Provider-specific parameters
]);
```

### Step 5: Test

```bash
php artisan tinker

# Test SMS sending
$sms = new App\Services\SmsService();
$sms->sendOTP('01712345678', '123456');
```

## Troubleshooting

### Issue: OTP Not Received (Email)

**Solution**:

-   Check mail configuration in `.env`
-   Check `storage/logs/laravel.log` for errors
-   Use Mailtrap for testing
-   Check spam folder

### Issue: OTP Not Received (SMS)

**Solution**:

-   Check `SMS_API_KEY` is set
-   Check `storage/logs/laravel.log` for OTP (if not configured)
-   Verify phone number format
-   Check SMS provider API response in logs
-   Check SMS provider account balance

### Issue: OTP Expired

**Solution**:

-   Click "Resend Code" button
-   New OTP valid for 10 minutes
-   Previous OTP automatically invalidated

### Issue: Maximum Attempts Exceeded

**Solution**:

-   Request new OTP
-   Attempts counter resets with new OTP

### Issue: Middleware Redirect Loop

**Solution**:

-   Ensure verification routes are not in verified middleware group
-   Check user roles (admins bypass verification)
-   Check `is_verified` field in database

### Issue: User Can't Access Dashboard

**Solution**:

-   Verify `is_verified = 1` in database
-   Check verification method completion
-   Use skip verification in development mode

## Production Checklist

-   [ ] Configure production mail server (SMTP/SES)
-   [ ] Set up SMS provider account
-   [ ] Add SMS API credentials to production `.env`
-   [ ] Test both email and SMS in production
-   [ ] Disable skip verification option (`APP_DEBUG=false`)
-   [ ] Set up monitoring for OTP delivery failures
-   [ ] Configure rate limiting for OTP requests
-   [ ] Set up alerts for high verification failures
-   [ ] Test complete user flow end-to-end
-   [ ] Monitor SMS costs and usage

## Admin Features

### View Verification Status

In admin panel, you can check user verification status:

-   Email verified: `email_verified_at` is not null
-   Phone verified: `phone_verified_at` is not null
-   Fully verified: `is_verified = 1`

### Manual Verification

Admins can manually verify users if needed:

```php
$user = User::find($userId);
$user->update([
    'is_verified' => true,
    'email_verified_at' => now(),
    'phone_verified_at' => now(),
]);
```

## Best Practices

1. **Always Use HTTPS**: Protect OTP transmission
2. **Short Expiry**: 10 minutes is standard
3. **Attempt Limits**: Prevent brute force attacks
4. **Resend Cooldown**: Prevent spam/abuse
5. **Secure Logging**: Never log OTPs in production (only for development)
6. **Phone Validation**: Validate phone format before sending
7. **Email Validation**: Verify email format and deliverability
8. **User Feedback**: Clear messages for all states
9. **Fallback Methods**: Offer alternative verification if one fails
10. **Monitor Costs**: Track SMS usage and costs

## Future Enhancements

-   [ ] Add rate limiting per user
-   [ ] Implement backup verification methods
-   [ ] Add SMS template customization
-   [ ] Support for international phone numbers
-   [ ] Add verification status to user profile
-   [ ] Email/Phone change with re-verification
-   [ ] Two-factor authentication (2FA) option
-   [ ] WhatsApp OTP integration
-   [ ] Voice call OTP option
-   [ ] Analytics dashboard for verification metrics

## Support

For issues or questions:

-   Check `storage/logs/laravel.log`
-   Review this guide
-   Test in development mode first
-   Contact technical support

---

**Last Updated**: October 20, 2025
**Version**: 1.0
**Author**: K-Glow Development Team


