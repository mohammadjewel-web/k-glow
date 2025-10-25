<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\VerificationSetting;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = Setting::getAllGrouped();
        $verificationSettings = VerificationSetting::getAllGrouped();
        
        // Get specific settings for view
        $logo = Setting::get('logo', 'admin-assets/logo.png');
        $whiteLogo = Setting::get('white_logo', 'admin-assets/white-logo.png');
        $favicon = Setting::get('favicon', 'favicon.ico');
        $primaryColor = Setting::get('primary_color', '#f36c21');
        
        return view('admin.settings.index', compact('settings', 'verificationSettings', 'logo', 'whiteLogo', 'favicon', 'primaryColor'));
    }

    /**
     * Get all settings
     */
    public function getSettings()
    {
        try {
            $settings = Setting::getAll();
            
            return response()->json([
                'success' => true,
                'settings' => $settings,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading settings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load settings',
            ], 500);
        }
    }

    /**
     * Get settings by group
     */
    public function getSettingsByGroup($group)
    {
        try {
            $settings = Setting::getByGroup($group);
            
            return response()->json([
                'success' => true,
                'settings' => $settings,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading settings group: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load settings',
            ], 500);
        }
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('Updating settings:', $data);
            
            Setting::updateMany($data);
            
            Log::info('Settings updated successfully');
            
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating settings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get verification settings
     */
    public function getVerificationSettings()
    {
        try {
            $settings = VerificationSetting::all();
            $settingsArray = [];
            
            foreach ($settings as $setting) {
                $value = $setting->value;
                
                // Cast boolean values
                if ($setting->type === 'boolean') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }
                
                $settingsArray[$setting->key] = $value;
            }
            
            return response()->json([
                'success' => true,
                'settings' => $settingsArray,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading verification settings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load settings',
            ], 500);
        }
    }

    /**
     * Update verification settings
     */
    public function updateVerificationSettings(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('Updating verification settings:', $data);
            
            // Update each setting
            foreach ($data as $key => $value) {
                $setting = VerificationSetting::where('key', $key)->first();
                
                if ($setting) {
                    // Convert boolean strings to proper values
                    if ($setting->type === 'boolean') {
                        $value = ($value === 'true' || $value === '1' || $value === 1) ? 'true' : 'false';
                    }
                    
                    $setting->update(['value' => $value]);
                }
            }
            
            // Clear cache
            VerificationSetting::clearCache();
            Cache::flush();
            
            Log::info('Verification settings updated successfully');
            
            return response()->json([
                'success' => true,
                'message' => 'Verification settings updated successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating verification settings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload logo/favicon
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,ico|max:2048',
            'setting_key' => 'required|string',
        ]);

        try {
            $file = $request->file('file');
            $settingKey = $request->setting_key;
            
            // Delete old file if exists (from public/admin-assets)
            $oldSetting = Setting::where('key', $settingKey)->first();
            if ($oldSetting && $oldSetting->value && $oldSetting->value !== 'admin-assets/logo.png' && $oldSetting->value !== 'admin-assets/white-logo.png') {
                $oldFilePath = public_path($oldSetting->value);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            // Upload new file directly to public/admin-assets
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('admin-assets');
            
            // Create directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $filename);
            
            // Update setting
            $filePath = 'admin-assets/' . $filename;
            Setting::set($settingKey, $filePath, 'file', 'theme');
            
            // Clear cache
            Setting::clearCache();
            \Artisan::call('view:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file_path' => $filePath,
                'file_url' => asset($filePath),
            ]);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test SMS configuration
     */
    public function testSMS(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'api_key' => 'required|string',
            'client_id' => 'required|string',
            'sender_id' => 'required|string',
            'api_url' => 'required|string',
        ]);

        try {
            $phone = $request->phone;
            $testOTP = rand(100000, 999999);
            
            Log::info('Testing SMS configuration:', [
                'phone' => $phone,
                'api_url' => $request->api_url,
                'sender_id' => $request->sender_id,
            ]);
            
            // Format phone number
            $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
            if (substr($phone, 0, 3) === '+88') {
                $phone = substr($phone, 3);
            }
            if (substr($phone, 0, 2) === '88') {
                $phone = substr($phone, 2);
            }
            $phone = '88' . $phone;
            
            // Prepare message
            $message = "K-Glow Test SMS\n\nYour test OTP is: {$testOTP}\n\nThis is a test message to verify SMS configuration.";
            
            // Send test SMS using the provided credentials
            $response = \Illuminate\Support\Facades\Http::timeout(30)->get($request->api_url, [
                'ApiKey' => $request->api_key,
                'ClientId' => $request->client_id,
                'SenderId' => $request->sender_id,
                'Message' => $message,
                'MobileNumbers' => $phone,
                'Is_Unicode' => false,
                'Is_Flash' => false,
            ]);
            
            Log::info('SMS API Test Response:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['Success']) && $responseData['Success'] === true) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Test SMS sent successfully! OTP: ' . $testOTP,
                        'otp' => $testOTP,
                        'response' => $responseData,
                    ]);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'SMS API returned error: ' . ($responseData['Message'] ?? 'Unknown error'),
                    'response' => $responseData,
                ], 400);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'HTTP Error: ' . $response->status(),
                'response' => $response->body(),
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('SMS test failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset settings to default
     */
    public function resetSettings(Request $request)
    {
        try {
            $group = $request->input('group', 'all');
            
            Log::info('Resetting settings, group: ' . $group);
            
            if ($group === 'all') {
                // Delete all existing settings
                Setting::truncate();
                VerificationSetting::truncate();
                
                // Run seeders to restore defaults
                \Artisan::call('db:seed', ['--class' => 'SettingsSeeder', '--force' => true]);
                \Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\SettingsSeeder', '--force' => true]);
                
                // Re-run verification settings migration to restore defaults
                \DB::statement('SET FOREIGN_KEY_CHECKS=0');
                \Artisan::call('migrate:refresh', ['--path' => 'database/migrations/2025_10_20_181741_create_verification_settings_table.php', '--force' => true]);
                \DB::statement('SET FOREIGN_KEY_CHECKS=1');
                
                Log::info('All settings reset successfully');
            } else {
                // Reset specific group settings
                $settings = Setting::where('group', $group)->get();
                // Would need default values logic here
            }
            
            Setting::clearCache();
            VerificationSetting::clearCache();
            Cache::flush();
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Settings reset to default values successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Settings reset error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset settings: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a setting
     */
    public function deleteSetting($key)
    {
        try {
            $setting = Setting::where('key', $key)->first();
            
            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found',
                ], 404);
            }
            
            // Delete file if it's a file type
            if ($setting->type === 'file' && $setting->value) {
                Storage::delete('public/' . $setting->value);
            }
            
            $setting->delete();
            Cache::forget('setting_' . $key);
            Setting::clearCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Setting deleted successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Setting delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete setting',
            ], 500);
        }
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        try {
            Setting::clearCache();
            VerificationSetting::clearCache();
            Cache::flush();
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache',
            ], 500);
        }
    }
}
