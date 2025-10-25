# 🎛️ Verification Settings in Admin Panel - Complete Guide

## ✅ What's Been Implemented

A complete admin panel interface to manage both Email and SMS OTP verification settings.

---

## 📍 Access the Settings

**URL**: http://127.0.0.1:8000/admin/settings

**Navigation**:

1. Login to admin panel
2. Go to **Settings** (left sidebar)
3. Click **"Verification Settings"** in the settings menu

---

## 🎨 Features in Admin Panel

### 1. **Email Verification Settings**

Configure email OTP options:

-   ✅ **Enable/Disable** - Toggle email verification on/off
-   ✅ **OTP Code Length** - Set digits (4-8, default: 6)
-   ✅ **OTP Expiry Time** - Set minutes (1-60, default: 10)
-   ✅ **Maximum Attempts** - Limit failed attempts (3-10, default: 5)
-   ✅ **Resend Cooldown** - Wait time in seconds (30-300, default: 60)

### 2. **SMS Verification Settings**

Configure SMS OTP options:

-   ✅ **Enable/Disable** - Toggle SMS verification on/off
-   ✅ **OTP Code Length** - Set digits (4-8, default: 6)
-   ✅ **OTP Expiry Time** - Set minutes (1-60, default: 10)
-   ✅ **Maximum Attempts** - Limit failed attempts (3-10, default: 5)
-   ✅ **Resend Cooldown** - Wait time in seconds (30-300, default: 60)

**SMS API Configuration:**

-   ✅ **API Key** - Pre-filled with your credentials
-   ✅ **Client ID** - Pre-filled
-   ✅ **Sender ID** - Pre-filled (8809601010905)
-   ✅ **API URL** - Pre-filled (http://103.69.149.50/api/v2/SendSMS)
-   ✅ **Test Button** - Send test SMS to verify configuration

### 3. **Dual Verification Settings**

Manage "Both" verification option:

-   ✅ **Allow Both Methods** - Let users choose email + SMS
-   ✅ **Require Both** - Force dual verification (not recommended)

### 4. **General Verification Settings**

Control verification behavior:

-   ✅ **Skip in Debug Mode** - Allow skipping when APP_DEBUG=true
-   ✅ **Require for Checkout** - Block checkout until verified

---

## 🎯 How to Use

### Step 1: Access Settings

Navigate to: **Admin Panel → Settings → Verification Settings**

### Step 2: Configure Email Settings

1. Toggle "Enable" switch for email verification
2. Set OTP parameters (length, expiry, attempts, cooldown)
3. Leave enabled for production

### Step 3: Configure SMS Settings

1. Toggle "Enable" switch for SMS verification
2. Set OTP parameters
3. **Verify API credentials**:
    - API Key: `iSGZnhYmaTSPMiDzx3sfs3BXumiUQqR4sY6coSgEdwU=`
    - Client ID: `4cd3f386-56d5-4e1d-8083-441713cad419`
    - Sender ID: `8809601010905`
4. Click **"Test SMS Configuration"** button
5. Enter test phone number
6. Verify SMS is received

### Step 4: Configure Dual Verification

1. Enable "Allow Both Email & SMS Verification"
2. Optionally enable "Require Both" (forces users to verify both)

### Step 5: Set General Options

1. Toggle "Allow Skip in Debug Mode" (for testing)
2. Toggle "Require Verification for Checkout"

### Step 6: Save Settings

Click **"Save Verification Settings"** button at the bottom

---

## 🧪 Testing SMS from Admin Panel

### In-Panel Test:

1. Go to **Settings → Verification Settings**
2. Scroll to SMS API Configuration
3. Click **"Test SMS Configuration"**
4. Enter your phone number (e.g., 01712345678)
5. Click OK
6. Check your phone for test SMS
7. Success message will show if SMS sent

### Test Message Format:

```
K-Glow Test SMS

Your test OTP is: 123456

This is a test message to verify SMS configuration.
```

---

## 📊 Settings Database Structure

All settings stored in `verification_settings` table:

| Key                                  | Default    | Description               |
| ------------------------------------ | ---------- | ------------------------- |
| `email_verification_enabled`         | true       | Email verification toggle |
| `email_otp_length`                   | 6          | Email OTP digits          |
| `email_otp_expiry`                   | 10         | Email OTP minutes         |
| `email_max_attempts`                 | 5          | Email max attempts        |
| `email_resend_cooldown`              | 60         | Email resend seconds      |
| `sms_verification_enabled`           | true       | SMS verification toggle   |
| `sms_otp_length`                     | 6          | SMS OTP digits            |
| `sms_otp_expiry`                     | 10         | SMS OTP minutes           |
| `sms_max_attempts`                   | 5          | SMS max attempts          |
| `sms_resend_cooldown`                | 60         | SMS resend seconds        |
| `sms_api_key`                        | (from env) | SMS API key               |
| `sms_client_id`                      | (from env) | SMS client ID             |
| `sms_sender_id`                      | (from env) | SMS sender ID             |
| `sms_api_url`                        | (from env) | SMS API URL               |
| `both_verification_enabled`          | true       | Allow both methods        |
| `both_verification_required`         | false      | Require both methods      |
| `skip_verification_in_debug`         | true       | Skip option in debug      |
| `verification_required_for_checkout` | true       | Checkout verification     |

---

## 🔄 How Settings Are Applied

### Immediate Effect:

When you save settings, they take effect immediately for:

-   New registrations
-   New OTP requests
-   Resend functionality
-   Verification page display

### No Restart Required:

Settings are cached and automatically refreshed on save.

---

## 🎯 Common Configuration Scenarios

### Scenario 1: Email Only (Simple)

```
✅ Email Verification: ON
❌ SMS Verification: OFF
❌ Both Verification: OFF
```

### Scenario 2: SMS Only (Fast)

```
❌ Email Verification: OFF
✅ SMS Verification: ON
❌ Both Verification: OFF
```

### Scenario 3: User Choice (Recommended)

```
✅ Email Verification: ON
✅ SMS Verification: ON
✅ Allow Both: ON
❌ Require Both: OFF
```

### Scenario 4: Maximum Security

```
✅ Email Verification: ON
✅ SMS Verification: ON
✅ Allow Both: ON
✅ Require Both: ON (forces dual verification)
```

### Scenario 5: Development Mode

```
✅ Email Verification: ON
✅ SMS Verification: ON
✅ Skip in Debug: ON (allows skipping)
❌ Require for Checkout: OFF
```

---

## 🚀 API Endpoints

### Get Settings

```
GET /admin/settings/verification/get
```

**Response:**

```json
{
  "success": true,
  "settings": {
    "email_verification_enabled": true,
    "email_otp_length": "6",
    "sms_api_key": "iSGZnhYmaTSPMiDzx3sfs3BXumiUQqR4sY6coSgEdwU=",
    ...
  }
}
```

### Update Settings

```
POST /admin/settings/verification/update
```

**Body:**

```json
{
  "email_verification_enabled": "true",
  "email_otp_length": "6",
  "sms_verification_enabled": "true",
  ...
}
```

### Test SMS

```
POST /admin/settings/verification/test-sms
```

**Body:**

```json
{
    "phone": "01712345678",
    "api_key": "...",
    "client_id": "...",
    "sender_id": "...",
    "api_url": "..."
}
```

**Response:**

```json
{
    "success": true,
    "message": "Test SMS sent successfully! OTP: 123456",
    "otp": "123456"
}
```

---

## 🔍 Troubleshooting

### Settings Not Saving

**Solution:**

1. Check browser console for errors
2. Verify admin permissions
3. Check `storage/logs/laravel.log`
4. Clear browser cache

### Test SMS Not Working

**Solution:**

1. Verify API credentials are correct
2. Check phone number format
3. Check SMS balance with provider
4. Review logs: `storage/logs/laravel.log`
5. Ensure API URL is correct

### Settings Not Loading

**Solution:**

1. Run: `php artisan migrate`
2. Check database for `verification_settings` table
3. Clear cache: `php artisan cache:clear`
4. Refresh page

### SMS API Error

**Solution:**

1. Check internet connection
2. Verify API endpoint is accessible
3. Check API key format (no extra spaces)
4. Contact SMS provider support

---

## 📱 Pre-configured SMS Credentials

Your SMS API is already configured with:

```
API Key: iSGZnhYmaTSPMiDzx3sfs3BXumiUQqR4sY6coSgEdwU=
Client ID: 4cd3f386-56d5-4e1d-8083-441713cad419
Sender ID: 8809601010905
API URL: http://103.69.149.50/api/v2/SendSMS
```

These are pre-filled in the admin panel - you can modify them as needed.

---

## 💡 Best Practices

### For Production:

1. **Enable both methods** - Give users choice
2. **Don't require both** - Too restrictive
3. **Set reasonable expiry** - 10 minutes is standard
4. **Limit attempts** - 5 attempts prevents abuse
5. **Add cooldown** - 60 seconds prevents spam
6. **Require for checkout** - Ensures verified users
7. **Disable skip** - Set APP_DEBUG=false in production

### For Development:

1. **Enable skip option** - Fast testing
2. **Lower cooldowns** - Faster iteration
3. **Check logs** - Monitor OTP codes
4. **Test both methods** - Ensure both work

---

## 🎨 UI Features

The admin panel includes:

-   ✅ **Modern Teal/Green color scheme**
-   ✅ **Toggle switches** for enable/disable
-   ✅ **Visual indicators** for each setting
-   ✅ **Inline help text** for all options
-   ✅ **Color-coded sections** (Email: Blue, SMS: Green, Both: Purple)
-   ✅ **Test SMS button** with real-time feedback
-   ✅ **Save/Reset buttons** for easy management
-   ✅ **Responsive design** for mobile access

---

## 📋 Quick Reference

### Access URL:

```
http://127.0.0.1:8000/admin/settings
```

### Key Routes:

```
GET  /admin/settings - Settings page
GET  /admin/settings/verification/get - Get settings
POST /admin/settings/verification/update - Update settings
POST /admin/settings/verification/test-sms - Test SMS
```

### Default Values:

```
OTP Length: 6 digits
Expiry: 10 minutes
Max Attempts: 5
Resend Cooldown: 60 seconds
```

---

## ✨ What Admins Can Do

From the admin panel, you can:

1. **Enable/Disable Verification Methods**

    - Turn email verification on/off
    - Turn SMS verification on/off
    - Control availability of "Both" option

2. **Customize OTP Parameters**

    - Change OTP length (more digits = more secure)
    - Adjust expiry time (longer = more convenient)
    - Set attempt limits (lower = more secure)
    - Configure resend cooldown (prevent spam)

3. **Manage SMS API**

    - Update API credentials
    - Change sender ID
    - Switch API providers
    - Test configuration instantly

4. **Control User Experience**

    - Allow or force dual verification
    - Enable skip for development
    - Require verification before purchase

5. **Monitor and Test**
    - Send test SMS to verify setup
    - View configuration status
    - Check API integration

---

## 🔐 Security Features

Built-in security measures:

-   ✅ Admin-only access (permission required)
-   ✅ CSRF protection on all requests
-   ✅ Input validation
-   ✅ Secure credential storage
-   ✅ Logging of all changes
-   ✅ Cache invalidation on save

---

## 📊 Integration

Settings automatically integrate with:

-   Registration flow
-   OTP generation
-   Verification pages
-   SMS service
-   Email notifications
-   User model

No code changes needed - update settings in admin panel!

---

## 🎉 Summary

You now have a **complete admin interface** to manage OTP verification:

✅ **Email Settings** - Full control over email OTP  
✅ **SMS Settings** - Complete SMS API management  
✅ **Dual Verification** - Control both methods option  
✅ **Test Functionality** - Test SMS directly from panel  
✅ **Real-time Updates** - Changes apply immediately  
✅ **Professional UI** - Modern, responsive design

**Everything is configurable from the admin panel - no code editing required!** 🚀

---

**Last Updated**: October 20, 2025  
**Status**: ✅ Fully Operational  
**Access**: Admin Panel → Settings → Verification Settings


