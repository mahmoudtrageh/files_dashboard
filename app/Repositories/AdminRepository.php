<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class AdminRepository implements AdminRepositoryInterface
{    
    public function getPaginated(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Admin::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        return $query->with('roles')
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
    }

    public function create(array $data): Admin
    {
        $admin = new Admin();
        $admin->name = $data['name'];
        $admin->email = $data['email'];
        $admin->password = Hash::make($data['password']);
        $admin->status = $data['status'] ?? false;
        
        if (isset($data['profile_image'])) {
            $admin
            ->addMedia($data['profile_image'])
            ->toMediaCollection('profile_image', 'public');
        }
        
        $admin->save();
        
        if (isset($data['roles']) && !empty($data['roles'])) {
            $admin->roles()->attach($data['roles']);
        }
        
        return $admin;
    }

    public function update(Admin $admin, array $data): Admin
    {
        $admin->name = $data['name'];
        $admin->email = $data['email'];
        
        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }
        
        $admin->status = $data['status'] ?? false;
        
        if (isset($data['profile_image'])) {
            $admin->clearMediaCollection('profile_image');
        
            $admin->addMediaFromRequest('profile_image')
                ->toMediaCollection('profile_image');
        }
        
        $admin->save();
        
        if (isset($data['roles'])) {
            $admin->roles()->sync($data['roles']);
        } else {
            $admin->roles()->detach();
        }
        
        return $admin;
    }

    public function delete(Admin $admin): bool
    {
        $admin->roles()->detach();
        
        return $admin->delete();
    }

    public function getWithDetails(Admin $admin): Admin
    {
        return $admin->load('roles.permissions');
    }

    public function isCurrentUser(Admin $admin): bool
    {
        return auth()->id() === $admin->id;
    }
}