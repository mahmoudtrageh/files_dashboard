<?php

namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('settings', function ($app) {
            return new SettingsService();
        });
    }

    public function boot(): void
    {
        $timestamp = date('Y_m_d_His', time());
        $this->publishes([
            __DIR__.'/../database/migrations/create_settings_table.php' => database_path("/migrations/{$timestamp}_create_settings_table.php"),
        ], 'settings-migrations');
    }
}