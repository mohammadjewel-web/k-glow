# Email Configuration Guide for K-Glow

## Your Email Credentials

**Email Provider**: Bismillah Goods Mail Server  
**Email Address**: jp@bismillahgoods.com  
**Password**: bV6{PmMCzYcB

**Server Details:**

-   **Incoming Server**: mail.bismillahgoods.com
    -   IMAP Port: 993
    -   POP3 Port: 995
-   **Outgoing Server**: mail.bismillahgoods.com
    -   SMTP Port: 465

---

## Step-by-Step Configuration

### Step 1: Open Your .env File

Open the `.env` file in the root directory of your project.

If `.env` doesn't exist, create it by copying `.env.example`:

```bash
copy .env.example .env
```

### Step 2: Add/Update Mail Configuration

Find the mail configuration section in your `.env` file and update it with these values:

```env
# ==============================================================================
# MAIL CONFIGURATION - Bismillah Goods Mail Server
# ==============================================================================

MAIL_MAILER=smtp
MAIL_HOST=mail.bismillahgoods.com
MAIL_PORT=465
MAIL_USERNAME=jp@bismillahgoods.com
MAIL_PASSWORD="bV6{PmMCzYcB"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=jp@bismillahgoods.com
MAIL_FROM_NAME="K-Glow"

# Alternative: Use TLS with port 587 if SSL doesn't work
# MAIL_PORT=587
# MAIL_ENCRYPTION=tls
```

### Step 3: Update App Configuration

Make sure these app settings are correct:

```env
APP_NAME="K-Glow"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://k-glowbd.com
```

### Step 4: Clear Configuration Cache

After updating `.env`, clear the configuration cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

---

## Complete .env Mail Section

Here's the complete mail configuration section to add/replace in your `.env` file:

```env
# ==============================================================================
# APPLICATION
# ==============================================================================
APP_NAME="K-Glow"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://k-glowbd.com

# ==============================================================================
# MAIL CONFIGURATION
# ==============================================================================
MAIL_MAILER=smtp
MAIL_HOST=mail.bismillahgoods.com
MAIL_PORT=465
MAIL_USERNAME=jp@bismillahgoods.com
MAIL_PASSWORD="bV6{PmMCzYcB"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=jp@bismillahgoods.com
MAIL_FROM_NAME="K-Glow"

# ==============================================================================
# SMS CONFIGURATION (Optional - for OTP)
# ==============================================================================
SMS_API_KEY=
SMS_SENDER_ID=K-GLOW
SMS_API_URL=

# ==============================================================================
# SSLCOMMERZ PAYMENT GATEWAY
# ==============================================================================
SSLCOMMERZ_STORE_ID=your_store_id
SSLCOMMERZ_STORE_PASSWORD=your_store_password
SSLCZ_TESTMODE=true
IS_LOCALHOST=false
```

---

## Testing Email Configuration

### Method 1: Artisan Tinker

```bash
php artisan tinker

# Send test email
Mail::raw('Test email from K-Glow', function($message) {
    $message->to('test@example.com')
            ->subject('Test Email');
});
```

### Method 2: Register New User

1. Go to your website registration page
2. Register a new account with a valid email
3. Check if OTP email is received
4. Verify the email format and content

### Method 3: Check Logs

Monitor Laravel logs for email sending:

```bash
tail -f storage/logs/laravel.log | grep -i mail
```

---

## Troubleshooting

### Issue 1: "Connection refused"

**Solution:**

-   Check if MAIL_HOST is correct: `mail.bismillahgoods.com`
-   Verify port 465 is open on your server
-   Try alternative port 587 with TLS encryption

### Issue 2: "Authentication failed"

**Solution:**

-   Verify username: `jp@bismillahgoods.com`
-   Verify password: `bV6{PmMCzYcB`
-   Make sure password is in quotes in .env
-   Clear config cache: `php artisan config:clear`

### Issue 3: SSL Certificate error

**Solution:**

-   Use port 587 with TLS instead of port 465 with SSL
-   Update .env:
    ```env
    MAIL_PORT=587
    MAIL_ENCRYPTION=tls
    ```

### Issue 4: Emails not sending

**Solution:**

-   Check spam/junk folder
-   Verify email server is not blocking PHP mail
-   Check firewall settings on server
-   Review Laravel logs: `storage/logs/laravel.log`

### Issue 5: "Stream_socket_enable_crypto() failed"

**Solution:**

-   This usually means SSL/TLS connection issue
-   Try switching between SSL (port 465) and TLS (port 587)
-   Update PHP OpenSSL extension

---

## Email Templates Used

The system sends emails for:

1. **OTP Verification** - 6-digit verification code
2. **Password Reset** - Password reset link
3. **Order Confirmation** - Order details and tracking
4. **Order Status Updates** - Shipping and delivery updates
5. **Contact Form** - Customer inquiries

All emails use the K-Glow branding and are mobile-responsive.

---

## Production Checklist

Before going live:

-   [ ] `.env` file updated with correct credentials
-   [ ] Config cache cleared (`php artisan config:clear`)
-   [ ] Test email sent successfully
-   [ ] OTP verification tested
-   [ ] Password reset tested
-   [ ] Order confirmation email tested
-   [ ] FROM name displays as "K-Glow"
-   [ ] FROM address is `jp@bismillahgoods.com`
-   [ ] Emails not going to spam
-   [ ] All links in emails work correctly
-   [ ] Email design looks good on mobile
-   [ ] SSL certificate valid for mail server

---

## Quick Configuration Commands

Run these commands in order after updating `.env`:

```bash
# 1. Clear all caches
php artisan optimize:clear

# 2. Test mail configuration
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('your-email@example.com')->subject('Test'); });
>>> exit

# 3. View logs for any errors
tail -f storage/logs/laravel.log
```

---

## Security Notes

1. **Never commit .env file** to version control
2. **Keep credentials secure** - don't share publicly
3. **Use environment variables** for all sensitive data
4. **Rotate password** regularly
5. **Monitor email logs** for suspicious activity
6. **Enable 2FA** on email account if available
7. **Use SSL/TLS** for encryption (already configured)

---

## Support

If you continue to have issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Test mail server with standalone PHP script
3. Contact Bismillah Goods support for server issues
4. Verify firewall/security settings
5. Try alternative ports (465 SSL or 587 TLS)

---

## Additional Resources

-   Laravel Mail Documentation: https://laravel.com/docs/mail
-   SMTP Troubleshooting Guide: https://laravel.com/docs/mail#troubleshooting
-   Email Testing: Use Mailtrap for development testing

---

**Last Updated**: October 20, 2025  
**Configuration For**: K-Glow E-commerce Platform  
**Email Server**: Bismillah Goods Mail Server


