<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'students.manage',
            'staff.manage',
            'academics.manage',
            'attendance.mark',
            'results.enter',
            'finance.manage',
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Model events (which auto-clear this cache) are suppressed by
        // DatabaseSeeder's WithoutModelEvents trait, so clear manually.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $admin = Role::findOrCreate('admin');
        $admin->givePermissionTo($permissions);

        $teacher = Role::findOrCreate('teacher');
        $teacher->givePermissionTo(['attendance.mark', 'results.enter']);

        Role::findOrCreate('staff');
        Role::findOrCreate('student');
        Role::findOrCreate('parent');
    }
}
