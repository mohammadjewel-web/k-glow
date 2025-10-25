# SMS API Setup Guide - Bangladesh Provider

## ✅ Your SMS API Credentials

**API Provider**: Bangladesh SMS Gateway (http://103.69.149.50)

**Credentials:**

```
API Key: iSGZnhYmaTSPMiDzx3sfs3BXumiUQqR4sY6coSgEdwU=
Client ID: 4cd3f386-56d5-4e1d-8083-441713cad419
Sender ID: 8809601010905
```

**API Endpoints:**

-   Send SMS: `http://103.69.149.50/api/v2/SendSMS`
-   Get Messages: `http://103.69.149.50/api/v2/SMS`

---

## 🚀 Quick Setup (3 Steps)

### Step 1: Add to .env File

Open your `.env` file and add these lines:

```env
# SMS API Configuration
SMS_API_KEY=iSGZnhYmaTSPMiDzx3sfs3BXumiUQqR4sY6coSgEdwU=
SMS_CLIENT_ID=4cd3f386-56d5-4e1d-8083-441713cad419
SMS_SENDER_ID=8809601010905
SMS_API_URL=http://103.69.149.50/api/v2/SendSMS
```

### Step 2: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 3: Test SMS

Run the test script:

```bash
php test_sms.php
```

Enter your phone number when prompted (e.g., `01712345678`)

---

## 📱 API Parameters

### Send SMS (GET Request)

**Endpoint**: `http://103.69.149.50/api/v2/SendSMS`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| ApiKey | String | Yes | Your API key (URL encoded) |
| ClientId | String | Yes | Your client ID (URL encoded) |
| SenderId | String | Yes | Sender ID (8809601010905) |
| Message | String | Yes | SMS message content |
| MobileNumbers | String | Yes | Phone number (88XXXXXXXXXX format) |
| Is_Unicode | Boolean | No | Unicode support (default: false) |
| Is_Flash | Boolean | No | Flash SMS (default: false) |

**Example Request:**

```
GET http://103.69.149.50/api/v2/SendSMS?ApiKey=iSGZnhYmaTSPMiDzx3sfs3BXumiUQqR4sY6coSgEdwU=&ClientId=4cd3f386-56d5-4e1d-8083-441713cad419&SenderId=8809601010905&Message=Your+OTP+is+123456&MobileNumbers=8801712345678&Is_Unicode=false&Is_Flash=false
```

**Success Response:**

```json
{
    "Success": true,
    "Message": "SMS sent successfully",
    "Data": {
        "MessageId": "...",
        "Status": "Sent"
    }
}
```

---

### Get Sent Messages (GET Request)

**Endpoint**: `http://103.69.149.50/api/v2/SMS`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| ApiKey | String | Yes | Your API key |
| ClientId | String | Yes | Your client ID |
| start | Number | No | Starting index (default: 0) |
| length | Number | No | Number of records (default: 100) |
| fromdate | String | No | Start date (yyyy-mm-dd) |
| enddate | String | No | End date (yyyy-mm-dd) |

**Example Request:**

```
GET http://103.69.149.50/api/v2/SMS?ApiKey=iSGZnhYmaTSPMiDzx3sfs3BXumiUQqR4sY6coSgEdwU=&ClientId=4cd3f386-56d5-4e1d-8083-441713cad419&start=0&length=10&fromdate=2025-10-01&enddate=2025-10-31
```

---

## 🧪 Testing

### Test 1: Run Test Script

```bash
php test_sms.php
```

**What it does:**

1. Shows your SMS configuration
2. Asks for test phone number
3. Sends OTP SMS
4. Shows result and logs

### Test 2: Register New User

1. Go to: http://127.0.0.1:8000/register
2. Fill registration form
3. Select "SMS Verification" or "Both"
4. Check your phone for OTP
5. Enter OTP and verify

### Test 3: Check Logs

```bash
Get-Content storage\logs\laravel.log -Tail 20 | Select-String "SMS"
```

Look for:

-   "Sending SMS:"
-   "SMS sent successfully"
-   Any error messages

---

## 📋 Phone Number Format

The system automatically formats phone numbers:

**Input formats (all work):**

-   `01712345678`
-   `8801712345678`
-   `+8801712345678`
-   `01712-345678`

**Output format (sent to API):**

-   `8801712345678` (always 88 + 11 digits)

---

## 🔍 Troubleshooting

### Issue 1: SMS Not Received

**Check:**

1. Phone number is correct and active
2. Balance in SMS account
3. Check logs: `storage/logs/laravel.log`
4. API response in logs

**Solution:**

```bash
# View recent SMS logs
Get-Content storage\logs\laravel.log -Tail 50 | Select-String "SMS"
```

### Issue 2: API Error

**Check logs for:**

-   HTTP status code
-   API response message
-   Connection errors

**Common fixes:**

-   Verify API credentials
-   Check internet connection
-   Verify API is not blocked by firewall

### Issue 3: Invalid Phone Number

**Solution:**

-   Use Bangladesh mobile numbers only
-   Format: 017/018/019/015/016/013 + 8 digits
-   System auto-formats to 88XXXXXXXXXX

### Issue 4: Timeout Error

**Solution:**

-   API might be slow
-   Check internet connection
-   Increased timeout to 30 seconds
-   Try again after a few minutes

---

## 📊 Success Indicators

When SMS is sent successfully, you'll see:

**In Logs:**

```
[2025-10-20 17:45:00] local.INFO: Sending SMS: {"phone":"8801712345678","message":"Your K-Glow verification code is: 123456..."}
[2025-10-20 17:45:01] local.INFO: SMS API Response: {"status":200,"body":"..."}
[2025-10-20 17:45:01] local.INFO: SMS sent successfully to: 8801712345678
```

**In Test Script:**

```
✅ SMS sent successfully!
📱 Test OTP: 123456
Check your phone for the message.
```

---

## 🔒 Security Best Practices

1. **Never commit** `.env` file to git
2. **Keep API key secure** - don't share publicly
3. **Monitor usage** - check for unusual activity
4. **Rate limiting** - implement limits to prevent abuse
5. **Log all attempts** - track SMS sending for debugging
6. **Validate numbers** - check format before sending
7. **Handle errors** - gracefully handle API failures

---

## 💰 Cost Management

1. **Monitor Balance**: Check SMS credit balance regularly
2. **Set Alerts**: Get notified when balance is low
3. **Track Usage**: Use the "Get Sent Messages" API
4. **Optimize Messages**: Keep under 160 characters
5. **Test Carefully**: Use real numbers sparingly in development

---

## 📈 Production Checklist

Before going live:

-   [✅] SMS API credentials added to `.env`
-   [✅] Config cache cleared
-   [✅] Test SMS sent successfully
-   [ ] Tested with multiple phone numbers
-   [ ] Verified SMS delivery rate
-   [ ] Set up monitoring for failures
-   [ ] Configured balance alerts
-   [ ] Rate limiting implemented
-   [ ] Error handling tested
-   [ ] Logs reviewed for issues

---

## 🛠️ Advanced Usage

### Send Custom SMS

```php
use App\Services\SmsService;

$sms = new SmsService();
$result = $sms->send('01712345678', 'Custom message here');

if ($result) {
    echo "SMS sent!";
} else {
    echo "Failed to send SMS";
}
```

### Get Sent Messages

```php
use App\Services\SmsService;

$sms = new SmsService();
$messages = $sms->getSentMessageList('2025-10-01', '2025-10-31', 0, 100);

print_r($messages);
```

### Check Configuration

```php
use App\Services\SmsService;

$sms = new SmsService();
$config = $sms->testConfiguration();

print_r($config);
```

---

## 📞 Support

**For API Issues:**

-   Check provider documentation
-   Contact API support team
-   Verify account status and balance

**For Code Issues:**

-   Check `storage/logs/laravel.log`
-   Review this documentation
-   Test with `test_sms.php`

---

## 📝 Quick Commands

```bash
# Add credentials to .env
# (Copy from SMS_API_CREDENTIALS.txt)

# Clear caches
php artisan config:clear
php artisan cache:clear

# Test SMS
php test_sms.php

# View SMS logs
Get-Content storage\logs\laravel.log -Tail 50 | Select-String "SMS"

# Test OTP flow
# Register at: http://127.0.0.1:8000/register
```

---

## ✨ What's Working

After setup:

-   ✅ SMS OTP verification
-   ✅ Registration with phone verification
-   ✅ Automatic number formatting
-   ✅ Error logging and handling
-   ✅ Retry mechanism
-   ✅ Balance monitoring available

---

**Last Updated**: October 20, 2025  
**Provider**: Bangladesh SMS Gateway  
**Status**: ✅ Ready for Production


