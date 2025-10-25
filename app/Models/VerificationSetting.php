<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class VerificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * Get a setting value by key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $cacheKey = 'verification_setting_' . $key;
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     * 
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function set($key, $value)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return false;
        }
        
        // Cast value to string for storage
        $stringValue = is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
        
        $setting->update(['value' => $stringValue]);
        
        // Clear cache
        Cache::forget('verification_setting_' . $key);
        Cache::forget('verification_settings_all');
        
        return true;
    }

    /**
     * Get all settings grouped by category
     * 
     * @return array
     */
    public static function getAllGrouped()
    {
        return Cache::remember('verification_settings_all', 3600, function () {
            $settings = self::all();
            $grouped = [];
            
            foreach ($settings as $setting) {
                if (!isset($grouped[$setting->group])) {
                    $grouped[$setting->group] = [];
                }
                
                $grouped[$setting->group][$setting->key] = [
                    'value' => self::castValue($setting->value, $setting->type),
                    'type' => $setting->type,
                    'description' => $setting->description,
                ];
            }
            
            return $grouped;
        });
    }

    /**
     * Update multiple settings at once
     * 
     * @param array $settings
     * @return bool
     */
    public static function updateMany(array $settings)
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value);
        }
        
        return true;
    }

    /**
     * Cast value to appropriate type
     * 
     * @param string $value
     * @param string $type
     * @return mixed
     */
    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $settings = self::all();
        
        foreach ($settings as $setting) {
            Cache::forget('verification_setting_' . $setting->key);
        }
        
        Cache::forget('verification_settings_all');
    }

    /**
     * Check if email verification is enabled
     */
    public static function isEmailVerificationEnabled()
    {
        return self::get('email_verification_enabled', true);
    }

    /**
     * Check if SMS verification is enabled
     */
    public static function isSmsVerificationEnabled()
    {
        return self::get('sms_verification_enabled', true);
    }

    /**
     * Check if both verification is enabled
     */
    public static function isBothVerificationEnabled()
    {
        return self::get('both_verification_enabled', true);
    }

    /**
     * Check if both verification is required
     */
    public static function isBothVerificationRequired()
    {
        return self::get('both_verification_required', false);
    }

    /**
     * Get OTP expiry time in minutes
     */
    public static function getOtpExpiry($type = 'email')
    {
        $key = $type === 'email' ? 'email_otp_expiry' : 'sms_otp_expiry';
        return (int) self::get($key, 10);
    }

    /**
     * Get max verification attempts
     */
    public static function getMaxAttempts($type = 'email')
    {
        $key = $type === 'email' ? 'email_max_attempts' : 'sms_max_attempts';
        return (int) self::get($key, 5);
    }

    /**
     * Get resend cooldown in seconds
     */
    public static function getResendCooldown($type = 'email')
    {
        $key = $type === 'email' ? 'email_resend_cooldown' : 'sms_resend_cooldown';
        return (int) self::get($key, 60);
    }
}
