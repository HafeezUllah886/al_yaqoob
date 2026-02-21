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
            ['name' => 'Create Purchases', 'group' => 'Purchases'],
            ['name' => 'View Purchases', 'group' => 'Purchases'],
            ['name' => 'Edit Purchases', 'group' => 'Purchases'],
            ['name' => 'Delete Purchases', 'group' => 'Purchases'],
            ['name' => 'Create Accounts', 'group' => 'Accounts'],
            ['name' => 'Create Business Accounts', 'group' => 'Accounts'],
            ['name' => 'Create Vendor Accounts', 'group' => 'Accounts'],
            ['name' => 'Create Customer Accounts', 'group' => 'Accounts'],
            ['name' => 'Edit Accounts', 'group' => 'Accounts'],
            ['name' => 'View Business Accounts', 'group' => 'Accounts'],
            ['name' => 'View Vendor Accounts', 'group' => 'Accounts'],
            ['name' => 'View Customer Accounts', 'group' => 'Accounts'],
            ['name' => 'View Stocks', 'group' => 'Stocks'],
            ['name' => 'Transfer Stocks', 'group' => 'Stocks'],
            ['name' => 'Delete Transfer Stocks', 'group' => 'Stocks'],
            ['name' => 'Stock Adjustments', 'group' => 'Stocks'],
            ['name' => 'Delete Stock Adjustments', 'group' => 'Stocks'],
            ['name' => 'Create Expenses', 'group' => 'Expenses'],
            ['name' => 'Delete Expenses', 'group' => 'Expenses'],
            ['name' => 'Create Expense Categories', 'group' => 'Expenses'],
            ['name' => 'Edit Expense Categories', 'group' => 'Expenses'],
            ['name' => 'Transfers', 'group' => 'Transfers'],
            ['name' => 'Delete Transfers', 'group' => 'Transfers'],
            ['name' => 'Account Adjustments', 'group' => 'Account Adjustments'],
            ['name' => 'Delete Account Adjustments', 'group' => 'Account Adjustments'],
            ['name' => 'Receivings', 'group' => 'Receivings'],
            ['name' => 'Delete Receivings', 'group' => 'Receivings'],
            ['name' => 'Payments', 'group' => 'Payments'],
            ['name' => 'Delete Payments', 'group' => 'Payments'],
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
