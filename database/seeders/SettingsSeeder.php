<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'K-Glow BD', 'type' => 'string', 'group' => 'general', 'description' => 'Site name', 'is_public' => true],
            ['key' => 'site_url', 'value' => 'https://k-glowbd.com', 'type' => 'string', 'group' => 'general', 'description' => 'Site URL', 'is_public' => true],
            ['key' => 'admin_email', 'value' => 'admin@k-glowbd.com', 'type' => 'string', 'group' => 'general', 'description' => 'Admin email address', 'is_public' => false],
            ['key' => 'support_email', 'value' => 'support@k-glow.com', 'type' => 'string', 'group' => 'general', 'description' => 'Support email', 'is_public' => true],
            ['key' => 'default_currency', 'value' => 'BDT', 'type' => 'string', 'group' => 'general', 'description' => 'Default currency code', 'is_public' => true],
            ['key' => 'currency_symbol', 'value' => '৳', 'type' => 'string', 'group' => 'general', 'description' => 'Currency symbol', 'is_public' => true],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka', 'type' => 'string', 'group' => 'general', 'description' => 'Default timezone', 'is_public' => true],
            ['key' => 'language', 'value' => 'en', 'type' => 'string', 'group' => 'general', 'description' => 'Default language', 'is_public' => true],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'type' => 'string', 'group' => 'general', 'description' => 'Date format', 'is_public' => true],
            ['key' => 'time_format', 'value' => 'H:i:s', 'type' => 'string', 'group' => 'general', 'description' => 'Time format', 'is_public' => true],
            
            // Store Information
            ['key' => 'store_name', 'value' => 'K-Glow Bangladesh', 'type' => 'string', 'group' => 'store', 'description' => 'Store name', 'is_public' => true],
            ['key' => 'store_phone', 'value' => '+880 123 456 789', 'type' => 'string', 'group' => 'store', 'description' => 'Store phone', 'is_public' => true],
            ['key' => 'store_address', 'value' => 'Dhaka, Bangladesh', 'type' => 'string', 'group' => 'store', 'description' => 'Store address', 'is_public' => true],
            ['key' => 'store_city', 'value' => 'Dhaka', 'type' => 'string', 'group' => 'store', 'description' => 'Store city', 'is_public' => true],
            ['key' => 'store_state', 'value' => 'Dhaka Division', 'type' => 'string', 'group' => 'store', 'description' => 'Store state/division', 'is_public' => true],
            ['key' => 'store_postal_code', 'value' => '1000', 'type' => 'string', 'group' => 'store', 'description' => 'Store postal code', 'is_public' => true],
            ['key' => 'store_country', 'value' => 'Bangladesh', 'type' => 'string', 'group' => 'store', 'description' => 'Store country', 'is_public' => true],
            ['key' => 'business_hours', 'value' => 'Mon-Fri, 9AM-5PM', 'type' => 'string', 'group' => 'store', 'description' => 'Business hours', 'is_public' => true],
            
            // Theme Settings
            ['key' => 'primary_color', 'value' => '#f36c21', 'type' => 'string', 'group' => 'theme', 'description' => 'Primary brand color', 'is_public' => true],
            ['key' => 'secondary_color', 'value' => '#0a0a0a', 'type' => 'string', 'group' => 'theme', 'description' => 'Secondary color', 'is_public' => true],
            ['key' => 'accent_color', 'value' => '#fef3c7', 'type' => 'string', 'group' => 'theme', 'description' => 'Accent color', 'is_public' => true],
            ['key' => 'text_color', 'value' => '#1f2937', 'type' => 'string', 'group' => 'theme', 'description' => 'Primary text color', 'is_public' => true],
            ['key' => 'heading_color', 'value' => '#111827', 'type' => 'string', 'group' => 'theme', 'description' => 'Heading text color', 'is_public' => true],
            ['key' => 'background_color', 'value' => '#ffffff', 'type' => 'string', 'group' => 'theme', 'description' => 'Background color', 'is_public' => true],
            ['key' => 'footer_bg_color', 'value' => '#0a0a0a', 'type' => 'string', 'group' => 'theme', 'description' => 'Footer background', 'is_public' => true],
            ['key' => 'header_bg_color', 'value' => '#ffffff', 'type' => 'string', 'group' => 'theme', 'description' => 'Header background', 'is_public' => true],
            ['key' => 'primary_font', 'value' => 'Inter, sans-serif', 'type' => 'string', 'group' => 'theme', 'description' => 'Primary font family', 'is_public' => true],
            ['key' => 'heading_font', 'value' => 'Poppins, sans-serif', 'type' => 'string', 'group' => 'theme', 'description' => 'Heading font family', 'is_public' => true],
            ['key' => 'logo', 'value' => 'admin-assets/logo.png', 'type' => 'file', 'group' => 'theme', 'description' => 'Site logo', 'is_public' => true],
            ['key' => 'white_logo', 'value' => 'admin-assets/white-logo.png', 'type' => 'file', 'group' => 'theme', 'description' => 'White logo', 'is_public' => true],
            ['key' => 'favicon', 'value' => 'favicon.ico', 'type' => 'file', 'group' => 'theme', 'description' => 'Site favicon', 'is_public' => true],
            ['key' => 'home_slogan', 'value' => 'Discover Korean Beauty', 'type' => 'string', 'group' => 'general', 'description' => 'Homepage main slogan', 'is_public' => true],
            ['key' => 'home_sub_slogan', 'value' => 'Premium K-Beauty Products Delivered to Your Doorstep', 'type' => 'string', 'group' => 'general', 'description' => 'Homepage sub slogan', 'is_public' => true],
            
            // Email Settings
            ['key' => 'mail_mailer', 'value' => 'smtp', 'type' => 'string', 'group' => 'email', 'description' => 'Mail driver', 'is_public' => false],
            ['key' => 'mail_host', 'value' => 'mail.bismillahgoods.com', 'type' => 'string', 'group' => 'email', 'description' => 'Mail host', 'is_public' => false],
            ['key' => 'mail_port', 'value' => '465', 'type' => 'string', 'group' => 'email', 'description' => 'Mail port', 'is_public' => false],
            ['key' => 'mail_username', 'value' => 'jp@bismillahgoods.com', 'type' => 'string', 'group' => 'email', 'description' => 'Mail username', 'is_public' => false],
            ['key' => 'mail_password', 'value' => 'bV6{PmMCzYcB', 'type' => 'string', 'group' => 'email', 'description' => 'Mail password', 'is_public' => false],
            ['key' => 'mail_encryption', 'value' => 'ssl', 'type' => 'string', 'group' => 'email', 'description' => 'Mail encryption', 'is_public' => false],
            ['key' => 'mail_from_address', 'value' => 'jp@bismillahgoods.com', 'type' => 'string', 'group' => 'email', 'description' => 'Mail from address', 'is_public' => false],
            ['key' => 'mail_from_name', 'value' => 'K-Glow', 'type' => 'string', 'group' => 'email', 'description' => 'Mail from name', 'is_public' => false],
            
            // SEO Settings
            ['key' => 'meta_title', 'value' => 'K-Glow BD - Premium Beauty & Skincare Products', 'type' => 'string', 'group' => 'seo', 'description' => 'Default meta title', 'is_public' => true],
            ['key' => 'meta_description', 'value' => 'Premium beauty and skincare products in Bangladesh. Shop the latest cosmetics, skincare, and beauty essentials.', 'type' => 'string', 'group' => 'seo', 'description' => 'Default meta description', 'is_public' => true],
            ['key' => 'meta_keywords', 'value' => 'beauty, skincare, cosmetics, Bangladesh, makeup, skincare products', 'type' => 'string', 'group' => 'seo', 'description' => 'Default meta keywords', 'is_public' => true],
            ['key' => 'google_analytics', 'value' => '', 'type' => 'string', 'group' => 'seo', 'description' => 'Google Analytics ID', 'is_public' => false],
            ['key' => 'facebook_pixel', 'value' => '', 'type' => 'string', 'group' => 'seo', 'description' => 'Facebook Pixel ID', 'is_public' => false],
            ['key' => 'google_site_verification', 'value' => '', 'type' => 'string', 'group' => 'seo', 'description' => 'Google site verification code', 'is_public' => true],
            
            // Shipping Settings
            ['key' => 'free_shipping_threshold', 'value' => '1000', 'type' => 'string', 'group' => 'shipping', 'description' => 'Free shipping minimum amount', 'is_public' => true],
            ['key' => 'default_shipping_cost', 'value' => '60', 'type' => 'string', 'group' => 'shipping', 'description' => 'Default shipping cost', 'is_public' => true],
            ['key' => 'shipping_time', 'value' => '3-5', 'type' => 'string', 'group' => 'shipping', 'description' => 'Delivery time in days', 'is_public' => true],
            ['key' => 'express_shipping_cost', 'value' => '120', 'type' => 'string', 'group' => 'shipping', 'description' => 'Express shipping cost', 'is_public' => true],
            ['key' => 'express_shipping_time', 'value' => '1-2', 'type' => 'string', 'group' => 'shipping', 'description' => 'Express delivery time', 'is_public' => true],
            
            // Payment Settings
            ['key' => 'cash_on_delivery', 'value' => 'true', 'type' => 'boolean', 'group' => 'payment', 'description' => 'Enable COD', 'is_public' => true],
            ['key' => 'sslcommerz_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'payment', 'description' => 'Enable SSLCommerz', 'is_public' => true],
            ['key' => 'sslcommerz_store_id', 'value' => env('SSLCOMMERZ_STORE_ID', ''), 'type' => 'string', 'group' => 'payment', 'description' => 'SSLCommerz Store ID', 'is_public' => false],
            ['key' => 'sslcommerz_store_password', 'value' => env('SSLCOMMERZ_STORE_PASSWORD', ''), 'type' => 'string', 'group' => 'payment', 'description' => 'SSLCommerz Password', 'is_public' => false],
            ['key' => 'sslcommerz_testmode', 'value' => 'true', 'type' => 'boolean', 'group' => 'payment', 'description' => 'SSLCommerz test mode', 'is_public' => false],
            ['key' => 'bkash_enabled', 'value' => 'false', 'type' => 'boolean', 'group' => 'payment', 'description' => 'Enable bKash', 'is_public' => true],
            ['key' => 'nagad_enabled', 'value' => 'false', 'type' => 'boolean', 'group' => 'payment', 'description' => 'Enable Nagad', 'is_public' => true],
            
            // Security Settings
            ['key' => 'two_factor_auth', 'value' => 'false', 'type' => 'boolean', 'group' => 'security', 'description' => 'Enable 2FA', 'is_public' => false],
            ['key' => 'session_lifetime', 'value' => '120', 'type' => 'string', 'group' => 'security', 'description' => 'Session lifetime in minutes', 'is_public' => false],
            ['key' => 'password_min_length', 'value' => '8', 'type' => 'string', 'group' => 'security', 'description' => 'Minimum password length', 'is_public' => false],
            ['key' => 'require_strong_password', 'value' => 'true', 'type' => 'boolean', 'group' => 'security', 'description' => 'Require strong passwords', 'is_public' => false],
            ['key' => 'login_attempts_limit', 'value' => '5', 'type' => 'string', 'group' => 'security', 'description' => 'Max login attempts', 'is_public' => false],
            ['key' => 'lockout_duration', 'value' => '15', 'type' => 'string', 'group' => 'security', 'description' => 'Lockout duration in minutes', 'is_public' => false],
            
            // Social Media
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/k-glow', 'type' => 'string', 'group' => 'store', 'description' => 'Facebook page URL', 'is_public' => true],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/k-glow', 'type' => 'string', 'group' => 'store', 'description' => 'Instagram profile URL', 'is_public' => true],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/k-glow', 'type' => 'string', 'group' => 'store', 'description' => 'YouTube channel URL', 'is_public' => true],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'string', 'group' => 'store', 'description' => 'Twitter profile URL', 'is_public' => true],
            ['key' => 'tiktok_url', 'value' => '', 'type' => 'string', 'group' => 'store', 'description' => 'TikTok profile URL', 'is_public' => true],
            
            // Tax & Fees
            ['key' => 'tax_rate', 'value' => '0', 'type' => 'string', 'group' => 'general', 'description' => 'Tax rate percentage', 'is_public' => true],
            ['key' => 'tax_enabled', 'value' => 'false', 'type' => 'boolean', 'group' => 'general', 'description' => 'Enable tax calculation', 'is_public' => true],
            
            // Maintenance
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group' => 'general', 'description' => 'Site maintenance mode', 'is_public' => true],
            ['key' => 'maintenance_message', 'value' => 'We are currently under maintenance. Please check back soon!', 'type' => 'string', 'group' => 'general', 'description' => 'Maintenance message', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
