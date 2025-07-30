<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admin\AdminIndexRequest;
use App\Http\Requests\Admin\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\Admin\UpdateAdminRequest;
use App\Models\Admin;
use App\Services\AdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    
    public function index(AdminIndexRequest $request): View
    {
        $admins = $this->adminService->getPaginatedAdmins(
            $request->search,
            $request->get('per_page', 2)
        );
        
        return view('admin.pages.admins.index', compact('admins'));
    }

    public function create()
    {
        if (!auth()->guard('admin')->user()->can('admin.create')) {
            return to_route('admin.admins.index')
                ->with('error', 'ليس لديك صلاحيات لإنشاء مشرف جديد.');
        }
        
        $roles = Role::get(['id', 'name']);
        return view('admin.pages.admins.create', compact('roles'));
    }

    public function store(StoreAdminRequest $request): RedirectResponse
    {                
        $this->adminService->createAdmin($request->all());
        
        return to_route('admin.admins.index')
            ->with('success', 'تم إضافة المشرف بنجاح.');
    }

    public function show(Admin $admin)
    {
        if (!auth()->guard('admin')->user()->can('admin.view', $admin)) {
            return to_route('admin.admins.index')
                ->with('error', 'ليس لديك صلاحيات لعرض تفاصيل هذا المشرف.');
        }

        $admin = $this->adminService->getAdminWithDetails($admin);
                
        return view('admin.pages.admins.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        if (!auth()->guard('admin')->user()->can('admin.edit', $admin)) {
            return to_route('admin.admins.index')
                ->with('error', 'ليس لديك صلاحيات لتعديل بيانات هذا المشرف.');
        }

        $roles = Role::get(['id', 'name']);
        return view('admin.pages.admins.edit', compact('admin', 'roles'));
    }

    public function update(UpdateAdminRequest $request, Admin $admin): RedirectResponse
    {     
        $this->adminService->updateAdmin($admin, $request->all());
        
        return to_route('admin.admins.index')
            ->with('success', 'تم تحديث بيانات المشرف بنجاح.');
    }

    public function destroy(Admin $admin)
    {
        if (!auth()->guard('admin')->user()->can('admin.delete', $admin)) {
            return to_route('admin.admins.index')
                ->with('error', 'ليس لديك صلاحيات لحذف هذا المشرف.');
        }

        if ($this->adminService->isCurrentUser($admin)) {
            return to_route('admin.admins.index')
                ->with('error', 'لا يمكنك حذف حسابك الحالي.');
        }
        
        $this->adminService->deleteAdmin($admin);
        
        return to_route('admin.admins.index')
            ->with('success', 'تم حذف المشرف بنجاح.');
    }
}