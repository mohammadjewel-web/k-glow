<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('verification_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, json
            $table->string('group')->default('verification');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('verification_settings')->insert([
            // Email Settings
            [
                'key' => 'email_verification_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'email',
                'description' => 'Enable email OTP verification',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'email_otp_length',
                'value' => '6',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Length of email OTP code',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'email_otp_expiry',
                'value' => '10',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Email OTP expiry time in minutes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'email_max_attempts',
                'value' => '5',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Maximum verification attempts for email OTP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'email_resend_cooldown',
                'value' => '60',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Cooldown time in seconds before resending email OTP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // SMS Settings
            [
                'key' => 'sms_verification_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'sms',
                'description' => 'Enable SMS OTP verification',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_otp_length',
                'value' => '6',
                'type' => 'string',
                'group' => 'sms',
                'description' => 'Length of SMS OTP code',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_otp_expiry',
                'value' => '10',
                'type' => 'string',
                'group' => 'sms',
                'description' => 'SMS OTP expiry time in minutes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_max_attempts',
                'value' => '5',
                'type' => 'string',
                'group' => 'sms',
                'description' => 'Maximum verification attempts for SMS OTP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_resend_cooldown',
                'value' => '60',
                'type' => 'string',
                'group' => 'sms',
                'description' => 'Cooldown time in seconds before resending SMS OTP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_api_key',
                'value' => env('SMS_API_KEY', ''),
                'type' => 'string',
                'group' => 'sms',
                'description' => 'SMS API Key',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_client_id',
                'value' => env('SMS_CLIENT_ID', ''),
                'type' => 'string',
                'group' => 'sms',
                'description' => 'SMS Client ID',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_sender_id',
                'value' => env('SMS_SENDER_ID', ''),
                'type' => 'string',
                'group' => 'sms',
                'description' => 'SMS Sender ID',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_api_url',
                'value' => env('SMS_API_URL', 'http://103.69.149.50/api/v2/SendSMS'),
                'type' => 'string',
                'group' => 'sms',
                'description' => 'SMS API URL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Both Settings
            [
                'key' => 'both_verification_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'both',
                'description' => 'Allow users to verify both email and SMS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'both_verification_required',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'both',
                'description' => 'Require both email and SMS verification',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // General Settings
            [
                'key' => 'skip_verification_in_debug',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Allow skipping verification in debug mode',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'verification_required_for_checkout',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Require verification before checkout',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_settings');
    }
};
