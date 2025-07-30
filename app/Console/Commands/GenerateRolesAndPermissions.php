<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\DynamicRolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class GenerateRolesAndPermissions extends Command
{
    protected $signature = 'permissions:generate 
                            {--fresh : Whether to clear existing roles and permissions}
                            {--model= : Generate permissions for a specific model only}
                            {--config= : Use a custom config file}';
    
    protected $description = 'Generate roles and permissions based on application models';

    public function handle(): int
    {
        // Check if tables exist
        if (!Schema::hasTable('permissions') || !Schema::hasTable('roles')) {
        $this->error('Permission or role tables do not exist. Have you run migrations?');
        $this->info('Run: php artisan migrate');
        return Command::FAILURE;
    }

    if ($this->option('fresh')) {
        $this->info('Clearing existing roles and permissions...');

        // Delete all roles and permissions
        Role::query()->delete();
        Permission::query()->delete();

        $this->info('Existing roles and permissions cleared.');
    }

    $this->info('Generating roles and permissions...');

    $seeder = new DynamicRolesAndPermissionsSeeder();
    $seeder->setCommand($this);

    // Load custom configuration if specified
    $configPath = $this->option('config');
    if ($configPath && file_exists($configPath)) {
        $customConfig = require $configPath;
        $seeder->setConfig($customConfig);
        $this->info("Using custom config from: {$configPath}");
    }

    // Filter for a specific model if requested
    $modelName = $this->option('model');
    if ($modelName) {
        $customConfig = [
            'excludedModels' => collect(config('permissions.excluded_models', []))
            ->filter(fn($model) => $model !== $modelName)
            ->toArray()
        ];
        $seeder->setConfig($customConfig);
        $this->info("Generating permissions only for model: {$modelName}");
    }

    $seeder->run();

    $this->info('Roles and permissions generated successfully!');

    return Command::SUCCESS;
    }
}
