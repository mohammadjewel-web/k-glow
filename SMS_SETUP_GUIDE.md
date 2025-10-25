# SMS Provider Setup Guide

## Quick Setup

### Step 1: Choose Your SMS Provider

Popular SMS providers in Bangladesh:

1. **BulkSMS Bangladesh** - https://www.bulksmsbd.com/
2. **SSL Wireless** - https://sslwireless.com/
3. **Grameenphone SMS API** - Contact GP Business
4. **Robi Axiata SMS API** - Contact Robi Business
5. **Banglalink SMS API** - Contact Banglalink Business

### Step 2: Get API Credentials

Sign up with your chosen provider and get:

-   **API Key** - Your authentication key
-   **Sender ID** - Your brand name (usually "K-GLOW", max 11 chars)
-   **API URL** - Provider's API endpoint

### Step 3: Add to Environment File

Add these lines to your `.env` file:

```env
# SMS Provider Configuration
SMS_API_KEY=your_api_key_here
SMS_SENDER_ID=K-GLOW
SMS_API_URL=https://api.your-provider.com/send
```

### Step 4: Update SMS Service (if needed)

If your provider uses a different API format, update `app/Services/SmsService.php`:

```php
public function send($phone, $message)
{
    $response = Http::post($this->apiUrl, [
        'api_key' => $this->apiKey,
        'sender_id' => $this->senderId,
        'phone' => $phone,
        'message' => $message,
        // Add your provider's specific parameters here
    ]);

    return $response->successful();
}
```

### Step 5: Test

Test SMS sending via tinker:

```bash
php artisan tinker

# Create SMS service
$sms = new App\Services\SmsService();

# Send test OTP
$sms->sendOTP('01712345678', '123456');
```

## Provider-Specific Examples

### BulkSMS Bangladesh

```env
SMS_API_KEY=your_bulksms_api_key
SMS_SENDER_ID=K-GLOW
SMS_API_URL=https://www.bulksmsbd.com/api/smsapi
```

API Format:

```php
$response = Http::post($this->apiUrl, [
    'api_key' => $this->apiKey,
    'type' => 'text',
    'contacts' => $phone,
    'senderid' => $this->senderId,
    'msg' => $message,
]);
```

### SSL Wireless

```env
SMS_API_KEY=your_ssl_api_key
SMS_SENDER_ID=K-GLOW
SMS_API_URL=https://sms.sslwireless.com/pushapi/dynamic/server.php
```

API Format:

```php
$response = Http::post($this->apiUrl, [
    'user' => 'your_username',
    'pass' => 'your_password',
    'sid' => $this->senderId,
    'msisdn' => $phone,
    'sms' => $message,
]);
```

## Development Mode

When `SMS_API_KEY` is not configured:

-   OTPs are logged to `storage/logs/laravel.log`
-   No SMS credits used
-   Perfect for local development

To view OTPs in development:

```bash
tail -f storage/logs/laravel.log | grep "SMS OTP"
```

## Testing Checklist

-   [ ] SMS API credentials configured
-   [ ] Test SMS sent successfully
-   [ ] Phone number format correct (88...)
-   [ ] OTP received within 30 seconds
-   [ ] Message content is clear and branded
-   [ ] Special characters display correctly
-   [ ] Multiple sends work properly
-   [ ] Error handling works correctly

## Cost Management

1. **Monitor Usage**: Check SMS balance regularly
2. **Set Alerts**: Configure low balance warnings
3. **Rate Limiting**: Implement per-user send limits
4. **Optimize Messages**: Keep under 160 characters
5. **Test Thoroughly**: Use development mode first

## Troubleshooting

### SMS Not Received

-   Check API credentials
-   Verify phone number format
-   Check SMS balance
-   Check `storage/logs/laravel.log` for errors
-   Test with different phone number
-   Contact provider support

### Invalid Phone Number

-   Ensure format is 88XXXXXXXXXX
-   Remove spaces and special characters
-   Verify number is active
-   Check country code

### API Error

-   Check API credentials
-   Verify API URL is correct
-   Check provider documentation
-   Test API with Postman first
-   Review error response in logs

## Security Best Practices

1. **Never commit** `.env` file to git
2. **Rotate credentials** regularly
3. **Use HTTPS** for API calls
4. **Limit rate** of SMS sends
5. **Monitor** for unusual activity
6. **Log** all SMS attempts
7. **Validate** phone numbers before sending
8. **Sanitize** message content

## Production Deployment

Before going live:

1. Test with real phone numbers
2. Verify SMS delivery rate
3. Set up monitoring alerts
4. Configure backup provider (if available)
5. Document credentials securely
6. Set up cost alerts
7. Train support team on SMS issues

## Support

For SMS-related issues:

-   Check provider's status page
-   Review API documentation
-   Contact provider support
-   Check Laravel logs
-   Test with provider's test numbers

---

**Note**: OTP functionality works without SMS configuration - OTPs will be logged for development use.


