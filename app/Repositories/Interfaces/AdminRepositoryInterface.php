<?php

namespace App\Repositories\Interfaces;

use App\Models\Admin;
use Illuminate\Pagination\LengthAwarePaginator;

interface AdminRepositoryInterface
{
    public function getPaginated(?string $search = null, int $perPage = 10): LengthAwarePaginator;
    
    public function create(array $data): Admin;
   
    public function update(Admin $admin, array $data): Admin;
    
    public function delete(Admin $admin): bool;
    
    public function getWithDetails(Admin $admin): Admin;
  
    public function isCurrentUser(Admin $admin): bool;
}