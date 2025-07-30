<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/permissions.php', 'permissions'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/permissions.php' => config_path('permissions.php'),
        ], 'permissions-config');
    }
}