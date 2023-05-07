<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();
        $permissions = [ 
            ['name' => 'user.user-list', 'guard_name' => 'sanctum'],
            ['name' => 'user.user-create', 'guard_name' => 'sanctum'],
            ['name' => 'user.user-update', 'guard_name' => 'sanctum'],
            ['name' => 'user.user-delete', 'guard_name' => 'sanctum'],
            ['name' => 'role.role-list', 'guard_name' => 'sanctum'],
            ['name' => 'role.role-create', 'guard_name' => 'sanctum'],
            ['name' => 'role.role-update', 'guard_name' => 'sanctum'],
            ['name' => 'role.role-delete', 'guard_name' => 'sanctum'],
            ['name' => 'patient.patient-list', 'guard_name' => 'sanctum'],
            ['name' => 'patient.patient-create', 'guard_name' => 'sanctum'],
            ['name' => 'patient.patient-update', 'guard_name' => 'sanctum'],
            ['name' => 'patient.patient-delete', 'guard_name' => 'sanctum'],
            ['name' => 'medicalrecord.medicalrecord-list', 'guard_name' => 'sanctum'],
            ['name' => 'medicalrecord.medicalrecord-create', 'guard_name' => 'sanctum'],
            ['name' => 'medicalrecord.medicalrecord-update', 'guard_name' => 'sanctum'],
            ['name' => 'medicalrecord.medicalrecord-delete', 'guard_name' => 'sanctum'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $data_permissions = Permission::all();

        foreach ($roles as $role) {
            foreach ($data_permissions as $permission) {
                $role->givePermissionTo($permission);
            }
        }
    }
}
