<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface
{
    public function getPaginated(?string $search = null, int $perPage = 10): LengthAwarePaginator;

    public function create(array $data): Role;
    
    public function update(Role $role, array $data): Role;

    public function delete(Role $role): bool;

    public function getWithPermissions(Role $role): Role;

    public function getUsersWithRole(Role $role, int $perPage = 10): LengthAwarePaginator;
    
    public function hasUsers(Role $role): bool;
    
    public function countUsersWithRole(Role $role): int;
}