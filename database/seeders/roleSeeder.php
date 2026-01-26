<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class roleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Operator',
                'guard_name' => 'web',
            ],

        ];
        foreach ($roles as $role) {
            Role::create($role);
        }

        $admin = Role::where('name', 'Admin')->first();
        $permissions = Permission::all();
        $admin->syncPermissions($permissions);
    }
}
