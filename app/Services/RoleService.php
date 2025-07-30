<?php

namespace App\Services;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    
    public function getPaginatedRoles(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->roleRepository->getPaginated($search, $perPage);
    }

    public function createRole(array $data): Role
    {
        return $this->roleRepository->create($data);
    }

    public function updateRole(Role $role, array $data): Role
    {
        return $this->roleRepository->update($role, $data);
    }

    public function deleteRole(Role $role): bool
    {
        return $this->roleRepository->delete($role);
    }

    public function getRoleWithPermissions(Role $role): Role
    {
        return $this->roleRepository->getWithPermissions($role);
    }

    public function getUsersWithRole(Role $role, int $perPage = 10): LengthAwarePaginator
    {
        return $this->roleRepository->getUsersWithRole($role, $perPage);
    }
    
    public function hasUsers(Role $role): bool
    {
        return $this->roleRepository->hasUsers($role);
    }

    public function countUsersWithRole(Role $role): int
    {
        return $this->roleRepository->countUsersWithRole($role);
    }
}