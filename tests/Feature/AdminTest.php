<?php

use App\Models\Admin;

use function Pest\Laravel\actingAs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'super-admin', 'guard_name' => 'admin']);
    $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'admin']);

    Permission::create(['name' => 'admin.create', 'guard_name' => 'admin']);
    
    $adminRole->givePermissionTo('admin.create');

});

test('super admin can access admins list page and see users', function () {

    $super_admin = Admin::factory()->superAdmin()->create();

    actingAs($super_admin, 'admin')
    ->get('dashboard/admins')
    ->assertStatus(200)
    ->assertSee('المشرفين');
});

test('admin cannot access admins list page', function () {

    $admin = Admin::factory()->admin()->create();

    actingAs($admin, 'admin')
    ->get('dashboard/admins')
    ->assertStatus(403);
});

test('admin can create admins', function () {

    $admin = Admin::factory()->admin()->create();

    actingAs($admin, 'admin')
    ->get('dashboard/admins/create')
    ->assertStatus(200);
});

