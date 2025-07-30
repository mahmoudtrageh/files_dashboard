<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function getPaginated(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Role::query()->where('guard_name', 'admin');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        $roles = $query->withCount('permissions')
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
        
        foreach ($roles as $role) {
            $role->users_count = $this->countUsersWithRole($role);
        }
        
        return $roles;
    }

    public function create(array $data): Role
    {
        DB::beginTransaction();
        
        try {
            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => 'admin'
            ]);
            
            if (isset($data['permissions']) && !empty($data['permissions'])) {
                $permissions = Permission::whereIn('id', $data['permissions'])->get();
                foreach ($permissions as $permission) {
                    $role->givePermissionTo($permission);
                }
            }
            
            DB::commit();
            
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Role $role, array $data): Role
    {
        DB::beginTransaction();
        
        try {
            $role->name = $data['name'];
            $role->save();
            
            if (isset($data['permissions'])) {
                foreach ($role->permissions as $permission) {
                    $role->revokePermissionTo($permission);
                }
                
                $permissions = Permission::whereIn('id', $data['permissions'])->get();
                foreach ($permissions as $permission) {
                    $role->givePermissionTo($permission);
                }
            } else {
                foreach ($role->permissions as $permission) {
                    $role->revokePermissionTo($permission);
                }
            }
            
            DB::commit();
            
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Role $role): bool
    {
        if ($this->hasUsers($role)) {
            throw new \Exception('لا يمكن حذف الدور لأنه مرتبط بمستخدمين.');
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($role->permissions as $permission) {
                $role->revokePermissionTo($permission);
            }
            
            $result = $role->delete();
            
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getWithPermissions(Role $role): Role
    {
        return $role->load('permissions');
    }

    public function getUsersWithRole(Role $role, int $perPage = 10): LengthAwarePaginator
    {
        return Admin::join('model_has_roles', function ($join) use ($role) {
                $join->on('model_has_roles.model_id', '=', 'admins.id')
                    ->where('model_has_roles.role_id', '=', $role->id)
                    ->where('model_has_roles.model_type', '=', 'App\\Models\\Admin');
            })
            ->select('admins.*')
            ->orderBy('admins.created_at', 'desc')
            ->paginate($perPage);
    }

    public function hasUsers(Role $role): bool
    {
        return DB::table('model_has_roles')
            ->where('role_id', $role->id)
            ->where('model_type', 'App\\Models\\Admin')
            ->exists();
    }

    public function countUsersWithRole(Role $role): int
    {
        return DB::table('model_has_roles')
            ->where('role_id', $role->id)
            ->where('model_type', 'App\\Models\\Admin')
            ->count();
    }
}