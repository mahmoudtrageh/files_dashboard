<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\RoleIndexRequest;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(RoleIndexRequest $request): View
    {
        $roles = $this->roleService->getPaginatedRoles(
            $request->search,
            $request->get('per_page', 3)
        );
        
        return view('admin.pages.roles.index', compact('roles'));
    }

    public function create(): View
    {
        if (!auth()->guard('admin')->user()->can('role.create')) {
            abort(403, 'Unauthorized action.');
        }

        $permissions = Permission::get(['id', 'name']);
        return view('admin.pages.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        try {
            $this->roleService->createRole([
                'name' => $request->name,
                'permissions' => $request->permissions,
            ]);
            
            return to_route('admin.roles.index')
                ->with('success', 'تم إضافة الدور بنجاح.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'حدث خطأ أثناء إضافة الدور: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Role $role): View
    {
        if (!auth()->guard('admin')->user()->can('role.view')) {
            abort(403, 'Unauthorized action.');
        }

        $role = $this->roleService->getRoleWithPermissions($role);
        $users = $this->roleService->getUsersWithRole($role, 10);
        
        return view('admin.pages.roles.show', compact('role', 'users'));
    }

    public function edit(Role $role): View
    {
        if (!auth()->guard('admin')->user()->can('role.edit')) {
            abort(403, 'Unauthorized action.');
        }
        
        $permissions = Permission::all();
        return view('admin.pages.roles.edit', compact('role', 'permissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        try {
            $this->roleService->updateRole($role, [
                'name' => $request->name,
                'permissions' => $request->permissions,
            ]);
            
           return to_route('admin.roles.index')
                ->with('success', 'تم تحديث الدور بنجاح.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'حدث خطأ أثناء تحديث الدور: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Role $role): RedirectResponse
    {
        if (!auth()->guard('admin')->user()->can('role.delete')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $this->roleService->deleteRole($role);
            
            return to_route('admin.roles.index')
                ->with('success', 'تم حذف الدور بنجاح.');
        } catch (\Exception $e) {
            return to_route('admin.roles.index')
                ->with('error', $e->getMessage());
        }
    }
}