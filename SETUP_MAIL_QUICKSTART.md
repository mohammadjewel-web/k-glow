# 📧 Quick Mail Setup Guide for K-Glow

## ⚡ Quick Setup (3 Steps)

### Step 1️⃣: Open Your .env File

Located at: `C:\Users\Jp-Asher\Documents\GitHub\k-glowbd.com\.env`

If it doesn't exist, create it from `.env.example`

### Step 2️⃣: Find and Replace Mail Settings

Look for lines starting with `MAIL_` and replace them with:

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.bismillahgoods.com
MAIL_PORT=465
MAIL_USERNAME=jp@bismillahgoods.com
MAIL_PASSWORD="bV6{PmMCzYcB"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=jp@bismillahgoods.com
MAIL_FROM_NAME="K-Glow"
```

### Step 3️⃣: Clear Cache

Open terminal in project folder and run:

```bash
php artisan config:clear
```

**Done!** ✅ Your email is now configured.

---

## 🧪 Test Your Configuration

### Quick Test via Tinker:

```bash
php artisan tinker
```

Then paste this:

```php
Mail::raw('Test email from K-Glow OTP System', function($message) {
    $message->to('your-test-email@gmail.com')
            ->subject('K-Glow Email Test');
});
```

Press Enter. If you see no errors, email sent successfully! Check your inbox.

Type `exit` to quit tinker.

---

## 🔍 Verify It's Working

### Method 1: Register New User

1. Go to: http://127.0.0.1:8000/register
2. Fill registration form
3. Select "Email Verification"
4. Check your email for OTP code
5. Enter OTP and verify

### Method 2: Check Logs

```bash
tail -f storage/logs/laravel.log
```

Look for:

-   "Email OTP sent to: [email]"
-   No error messages

---

## ⚠️ Common Issues & Fixes

### ❌ "Connection refused"

**Fix**: Check if mail server is accessible

```bash
ping mail.bismillahgoods.com
```

### ❌ "Authentication failed"

**Fix**:

1. Verify credentials are exactly:
    - Username: `jp@bismillahgoods.com`
    - Password: `bV6{PmMCzYcB`
2. Make sure password has quotes in .env
3. Run: `php artisan config:clear`

### ❌ SSL/TLS Errors

**Fix**: Try alternative configuration with port 587

```env
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

### ❌ Email not received

**Fix**:

1. Check spam/junk folder
2. Wait 1-2 minutes (may be delayed)
3. Check logs: `storage/logs/laravel.log`
4. Try with different email address

---

## 📋 Checklist

After configuration, verify:

-   [ ] `.env` file updated with mail settings
-   [ ] Ran `php artisan config:clear`
-   [ ] Tested with tinker (email sent successfully)
-   [ ] Registered test user and received OTP
-   [ ] OTP email looks professional with K-Glow branding
-   [ ] No errors in `storage/logs/laravel.log`

---

## 🎯 What This Enables

With email configured, these features now work:

✅ **OTP Email Verification** - New users receive 6-digit code
✅ **Password Reset** - Users can reset forgotten passwords  
✅ **Order Confirmations** - Customers get order details
✅ **Notifications** - System can send email alerts
✅ **Contact Form** - Admin receives customer messages

---

## 🚀 Quick Commands Reference

```bash
# Clear all caches
php artisan optimize:clear

# Clear only config cache
php artisan config:clear

# View Laravel logs
tail -f storage/logs/laravel.log

# Test email via tinker
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('test@example.com'); });
>>> exit

# Check mail configuration
php artisan tinker
>>> config('mail')
>>> exit
```

---

## 📞 Need Help?

1. **Check logs first**: `storage/logs/laravel.log`
2. **Review full guide**: `MAIL_CONFIGURATION.md`
3. **Test with simple email** before testing OTP system
4. **Try alternative port** 587 if 465 doesn't work

---

## 💡 Pro Tips

-   **Development**: Use Mailtrap (https://mailtrap.io) for testing
-   **Production**: Use your actual mail server (already configured)
-   **Debugging**: Always clear config cache after .env changes
-   **Security**: Never commit `.env` file to git
-   **Testing**: Test with multiple email providers (Gmail, Yahoo, etc.)

---

**✨ Configuration completed! Your OTP email verification system is now ready to use.**


