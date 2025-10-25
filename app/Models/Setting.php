<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
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
        $cacheKey = 'setting_' . $key;
        
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
     * @param string $type
     * @param string $group
     * @return bool
     */
    public static function set($key, $value, $type = 'string', $group = 'general')
    {
        // Cast value to string for storage
        $stringValue = is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
        
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $stringValue,
                'type' => $type,
                'group' => $group,
            ]
        );
        
        // Clear cache
        Cache::forget('setting_' . $key);
        Cache::forget('settings_all');
        Cache::forget('settings_group_' . $group);
        
        return true;
    }

    /**
     * Get all settings
     * 
     * @return array
     */
    public static function getAll()
    {
        return Cache::remember('settings_all', 3600, function () {
            $settings = self::all();
            $settingsArray = [];
            
            foreach ($settings as $setting) {
                $settingsArray[$setting->key] = self::castValue($setting->value, $setting->type);
            }
            
            return $settingsArray;
        });
    }

    /**
     * Get settings grouped by category
     * 
     * @return array
     */
    public static function getAllGrouped()
    {
        return Cache::remember('settings_grouped', 3600, function () {
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
     * Get settings by group
     * 
     * @param string $group
     * @return array
     */
    public static function getByGroup($group)
    {
        return Cache::remember('settings_group_' . $group, 3600, function () use ($group) {
            $settings = self::where('group', $group)->get();
            $settingsArray = [];
            
            foreach ($settings as $setting) {
                $settingsArray[$setting->key] = self::castValue($setting->value, $setting->type);
            }
            
            return $settingsArray;
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
            $existingSetting = self::where('key', $key)->first();
            
            if ($existingSetting) {
                // Convert boolean strings to proper values
                if ($existingSetting->type === 'boolean') {
                    $value = ($value === 'true' || $value === '1' || $value === 1 || $value === true) ? 'true' : 'false';
                }
                
                $existingSetting->update(['value' => $value]);
            }
        }
        
        // Clear all caches
        self::clearCache();
        
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
            Cache::forget('setting_' . $setting->key);
            Cache::forget('settings_group_' . $setting->group);
        }
        
        Cache::forget('settings_all');
        Cache::forget('settings_grouped');
    }

    /**
     * Get public settings (for frontend)
     */
    public static function getPublicSettings()
    {
        return Cache::remember('settings_public', 3600, function () {
            $settings = self::where('is_public', true)->get();
            $settingsArray = [];
            
            foreach ($settings as $setting) {
                $settingsArray[$setting->key] = self::castValue($setting->value, $setting->type);
            }
            
            return $settingsArray;
        });
    }

    /**
     * Helper methods for common settings
     */
    public static function getSiteName()
    {
        return self::get('site_name', 'K-Glow');
    }

    public static function getPrimaryColor()
    {
        return self::get('primary_color', '#f36c21');
    }

    public static function getTextColor()
    {
        return self::get('text_color', '#1f2937');
    }

    public static function getLogo()
    {
        return self::get('logo', 'admin-assets/logo.png');
    }

    public static function getFavicon()
    {
        return self::get('favicon', 'favicon.ico');
    }
}
