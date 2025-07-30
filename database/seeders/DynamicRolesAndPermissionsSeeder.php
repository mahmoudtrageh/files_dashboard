<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use ReflectionClass;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
class DynamicRolesAndPermissionsSeeder extends Seeder
{
    protected $command;

    protected string $modelsPath;
    protected array $excludedModels;
    protected array $standardActions;
    protected array $roles;
    protected array $customModelActions;
    protected array $customPermissions;
    protected string $adminEmail;
    protected string $guardName;

    public function __construct()
    {
        $this->modelsPath = config('permissions.models_path', 'app/Models');
        $this->excludedModels = config('permissions.excluded_models', [
            'User', 'Permission', 'Role', 'Model', 'BaseModel', 'Pivot'
        ]);
        $this->standardActions = config('permissions.standard_actions', [
            'view', 'create', 'edit', 'delete'
        ]);
        $this->roles = config('permissions.roles', [
            'admin' => ['all'],
            'editor' => ['view', 'create', 'edit'],
            'viewer' => ['view']
        ]);
        $this->customModelActions = config('permissions.custom_model_actions', []);
        $this->customPermissions = config('permissions.custom_permissions', [
            'settings.view',
            'settings.edit',
            'dashboard.view',
        ]);
        $this->adminEmail = config('permissions.admin_email', 'admin@example.com');
        $this->guardName = config('permissions.guard_name', 'admin');
    }

    public function setCommand(Command $command): self
    {
        $this->command = $command;
        return $this;
    }
    
    public function setConfig(array $config): self
    {
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        return $this;
    }

    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->log("Using guard: {$this->guardName}");

        $models = $this->getModels();
        $this->log("Found {$models->count()} models: {$models->implode(', ')}");

        $allPermissions = $this->createPermissions($models);

        $this->createRolesWithPermissions($allPermissions);

        $this->assignAdminRole();
        
        $this->log("Seeding completed successfully!");
    }

    protected function getModels(): Collection
    {
        $models = collect();
        $modelsPath = app_path(str_replace('app/', '', $this->modelsPath));

        if (!File::isDirectory($modelsPath)) {
            $this->log("Models directory not found at: {$modelsPath}");
            return $models;
        }

        $files = File::files($modelsPath);

        foreach ($files as $file) {
            $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            
            if (in_array($className, $this->excludedModels, true)) {
                continue;
            }

            $fullyQualifiedClassName = "App\\Models\\{$className}";
            
            if (!class_exists($fullyQualifiedClassName)) {
                continue;
            }

            try {
                $reflection = new ReflectionClass($fullyQualifiedClassName);
                if (!$reflection->isSubclassOf(Model::class) || $reflection->isAbstract()) {
                    continue;
                }
                
                $models->push($className);
            } catch (\Throwable $e) {
                $this->log("Error processing model {$className}: {$e->getMessage()}");
                continue;
            }
        }

        return $models;
    }

    protected function createPermissions(Collection $models): array
    {
        $allPermissions = [];

        foreach ($models as $model) {
            $modelName = Str::kebab($model);
            
            foreach ($this->standardActions as $action) {
                $permissionName = "{$modelName}.{$action}";
                
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => $this->guardName
                ]);
                $this->log("Created permission: {$permissionName} (guard: {$this->guardName})");
                
                $allPermissions[$model][] = $permissionName;
            }
            
            if (isset($this->customModelActions[$model])) {
                foreach ($this->customModelActions[$model] as $action) {
                    $permissionName = "{$modelName}.{$action}";
                    
                    Permission::firstOrCreate([
                        'name' => $permissionName,
                        'guard_name' => $this->guardName
                    ]);
                    $this->log("Created custom permission: {$permissionName} (guard: {$this->guardName})");
                    
                    $allPermissions[$model][] = $permissionName;
                }
            }
        }

        foreach ($this->customPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $this->guardName
            ]);
            $this->log("Created custom permission: {$permission} (guard: {$this->guardName})");
            $allPermissions['Custom'][] = $permission;
        }

        return $allPermissions;
    }

    protected function createRolesWithPermissions(array $allPermissions): void
    {
        foreach ($this->roles as $roleName => $allowedActions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => $this->guardName
            ]);
            $this->log("Created role: {$roleName} (guard: {$this->guardName})");
            
            if (in_array('all', $allowedActions, true)) {
                $role->syncPermissions(
                    Permission::where('guard_name', $this->guardName)->get()
                );
                $this->log("Assigned all permissions to role: {$roleName}");
            } else {
                $rolePermissions = collect();
                
                foreach ($allPermissions as $permissions) {
                    foreach ($permissions as $permission) {
                        $parts = explode('.', $permission);
                        $action = end($parts);
                        
                        if (in_array($action, $allowedActions, true)) {
                            $rolePermissions->push($permission);
                        }
                    }
                }
                
                $permissionModels = Permission::where('guard_name', $this->guardName)
                    ->whereIn('name', $rolePermissions)
                    ->get();
                
                $role->syncPermissions($permissionModels);
                $this->log("Assigned " . $permissionModels->count() . " permissions to role: {$roleName}");
            }
        }
    }

    protected function assignAdminRole(): void
    {
        $user = Admin::where('email', $this->adminEmail)->first();
        
        if ($user) {
            $adminRole = Role::where('name', 'admin')
                ->where('guard_name', $this->guardName)
                ->first();
                
            if ($adminRole) {
                $user->syncRoles([$adminRole]);
                $this->log("Admin role assigned to: {$user->email}");
            } else {
                $this->log("Admin role not found with guard: {$this->guardName}");
            }
        } else {
            $this->log("Admin user not found. No roles assigned.");
        }
    }
    
    protected function log(string $message): void
    {
        if ($this->command) {
            $this->command->info($message);
        } else {
            logger()->info($message);
        }
    }
}