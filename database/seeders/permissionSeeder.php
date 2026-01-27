<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class permissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'View Users', 'group' => 'Users'],
            ['name' => 'Create Users', 'group' => 'Users'],
            ['name' => 'Change User Password', 'group' => 'Users'],
            ['name' => 'Change User Status', 'group' => 'Users'],
            ['name' => 'View Roles', 'group' => 'Roles'],
            ['name' => 'Create Roles', 'group' => 'Roles'],
            ['name' => 'Edit Roles', 'group' => 'Roles'],
            ['name' => 'Delete Roles', 'group' => 'Roles'],
            ['name' => 'View Products', 'group' => 'Products'],
            ['name' => 'Create Products', 'group' => 'Products'],
            ['name' => 'Edit Products', 'group' => 'Products'],
            ['name' => 'Delete Products', 'group' => 'Products'],
            ['name' => 'View Branches', 'group' => 'Branches'],
            ['name' => 'Create Branches', 'group' => 'Branches'],
            ['name' => 'Edit Branches', 'group' => 'Branches'],
            ['name' => 'View Categories', 'group' => 'Categories'],
            ['name' => 'Create Categories', 'group' => 'Categories'],
            ['name' => 'Edit Categories', 'group' => 'Categories'],
            ['name' => 'Delete Categories', 'group' => 'Categories'],
            ['name' => 'View Units', 'group' => 'Units'],
            ['name' => 'Create Units', 'group' => 'Units'],
            ['name' => 'Edit Units', 'group' => 'Units'],
            ['name' => 'Delete Units', 'group' => 'Units'],
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
