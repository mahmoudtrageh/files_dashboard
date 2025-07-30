<?php

namespace App\Services;

use App\Models\Admin;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminService
{
    protected $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }
    
    public function getPaginatedAdmins(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->adminRepository->getPaginated($search, $perPage);
    }

    public function createAdmin(array $data): Admin
    {
        return $this->adminRepository->create($data);
    }

    public function updateAdmin(Admin $admin, array $data): Admin
    {        
        return $this->adminRepository->update($admin, $data);
    }

    public function deleteAdmin(Admin $admin): bool
    {
        return $this->adminRepository->delete($admin);
    }

    public function getAdminWithDetails(Admin $admin): Admin
    {
        return $this->adminRepository->getWithDetails($admin);
    }

    public function isCurrentUser(Admin $admin): bool
    {
        return $this->adminRepository->isCurrentUser($admin);
    }
}