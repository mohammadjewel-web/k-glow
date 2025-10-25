<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $apiKey;
    protected $clientId;
    protected $senderId;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('SMS_API_KEY', 'iSGZnhYmaTSPMiDzx3sfs3BXumiUQqR4sY6coSgEdwU=');
        $this->clientId = env('SMS_CLIENT_ID', '4cd3f386-56d5-4e1d-8083-441713cad419');
        $this->senderId = env('SMS_SENDER_ID', '8809601010905');
        $this->apiUrl = env('SMS_API_URL', 'http://103.69.149.50/api/v2/SendSMS');
    }

    /**
     * Send SMS
     * 
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function send($phone, $message)
    {
        try {
            // Format phone number (remove +88 if exists, ensure it starts with 88)
            $phone = $this->formatPhoneNumber($phone);

            Log::info('Sending SMS:', [
                'phone' => $phone,
                'message' => $message,
            ]);

            // If SMS API is not configured, log the OTP instead of failing
            if (empty($this->apiKey) || $this->apiKey === 'your_api_key_here') {
                Log::warning('SMS API not configured. OTP for ' . $phone . ': ' . $message);
                return true; // Return true for development
            }

            // Send SMS using the provider's API
            $response = Http::timeout(30)->get($this->apiUrl, [
                'ApiKey' => $this->apiKey,
                'ClientId' => $this->clientId,
                'SenderId' => $this->senderId,
                'Message' => $message,
                'MobileNumbers' => $phone,
                'Is_Unicode' => false,
                'Is_Flash' => false,
            ]);

            Log::info('SMS API Response:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check if the API returned success
                if (isset($responseData['Success']) && $responseData['Success'] === true) {
                    Log::info('SMS sent successfully to: ' . $phone, [
                        'response' => $responseData
                    ]);
                    return true;
                }
                
                Log::error('SMS sending failed - API returned error:', [
                    'phone' => $phone,
                    'response' => $responseData,
                ]);
                return false;
            }

            Log::error('SMS sending failed - HTTP error:', [
                'phone' => $phone,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('SMS sending error: ' . $e->getMessage(), [
                'phone' => $phone,
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Send OTP via SMS
     * 
     * @param string $phone
     * @param string $otp
     * @return bool
     */
    public function sendOTP($phone, $otp)
    {
        $message = "Your K-Glow verification code is: {$otp}\n\nThis code will expire in 10 minutes.\n\nDo not share this code with anyone.";
        return $this->send($phone, $message);
    }

    /**
     * Format phone number for Bangladesh
     * 
     * @param string $phone
     * @return string
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove any spaces, dashes, or parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
        
        // Remove +88 if exists
        if (substr($phone, 0, 3) === '+88') {
            $phone = substr($phone, 3);
        }
        
        // Remove 88 if exists at the start
        if (substr($phone, 0, 2) === '88') {
            $phone = substr($phone, 2);
        }
        
        // Add 88 prefix (required by the SMS API)
        return '88' . $phone;
    }

    /**
     * Get sent message list
     * 
     * @param string $startDate Format: yyyy-mm-dd
     * @param string $endDate Format: yyyy-mm-dd
     * @param int $start Starting index (default: 0)
     * @param int $length Number of records (default: 100)
     * @return array|null
     */
    public function getSentMessageList($startDate = null, $endDate = null, $start = 0, $length = 100)
    {
        try {
            $listApiUrl = 'http://103.69.149.50/api/v2/SMS';
            
            $params = [
                'ApiKey' => $this->apiKey,
                'ClientId' => $this->clientId,
                'start' => $start,
                'length' => $length,
            ];

            if ($startDate) {
                $params['fromdate'] = $startDate;
            }

            if ($endDate) {
                $params['enddate'] = $endDate;
            }

            $response = Http::timeout(30)->get($listApiUrl, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to get sent message list:', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Error getting sent message list: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Test SMS configuration
     * 
     * @return array
     */
    public function testConfiguration()
    {
        return [
            'api_key' => substr($this->apiKey, 0, 10) . '...',
            'client_id' => $this->clientId,
            'sender_id' => $this->senderId,
            'api_url' => $this->apiUrl,
            'configured' => !empty($this->apiKey) && $this->apiKey !== 'your_api_key_here',
        ];
    }
}
