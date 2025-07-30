<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        $this->seedDefaultSettings();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }

    /**
     * Seed default settings.
     */
    private function seedDefaultSettings(): void
    {
        $settings = [
            // General settings
            ['key' => 'site_name', 'value' => config('app.name')],
            ['key' => 'items_per_page', 'value' => '15'],
            ['key' => 'maintenance_mode', 'value' => '0'],
            ['key'=> 'panel_version', 'value'=> '1'],
            ['key'=> 'dashboard_logo', 'value'=> 'https://placehold.co/150'],
            ['key' => 'favicon', 'value' => 'https://placehold.co/32'],

            // SEO settings
            ['key' => 'meta_title', 'value' => config('app.name')],
            ['key' => 'meta_description', 'value' => 'Laravel Application'],
            ['key' => 'meta_keywords', 'value' => 'laravel, web, application'],

            // Contact settings
            ['key' => 'contact_email', 'value' => 'contact@example.com'],
            ['key' => 'phone_number', 'value' => '+201000000000'],
            ['key' => 'address', 'value' => 'القاهرة، مصر'],

            // Social media settings
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/'],
            ['key' => 'linkedin_url', 'value' => 'https://linkedin.com/'],

            // Advanced settings
            ['key' => 'cache_enabled', 'value' => '1'],
            ['key' => 'cache_expiration', 'value' => '86400'],
            ['key' => 'google_analytics_id', 'value' => ''],
            ['key' => 'custom_header_scripts', 'value' => ''],
        ];

        $now = now();

        foreach ($settings as &$setting) {
            $setting['created_at'] = $now;
            $setting['updated_at'] = $now;
        }

        DB::table('settings')->insert($settings);
    }
};
