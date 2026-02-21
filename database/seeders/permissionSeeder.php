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
            // users
            ['name' => 'View Users', 'group' => 'Users'],
            ['name' => 'Create Users', 'group' => 'Users'],
            ['name' => 'Change User Password', 'group' => 'Users'],
            ['name' => 'Change User Status', 'group' => 'Users'],
            // roles
            ['name' => 'View Roles', 'group' => 'Roles'],
            ['name' => 'Create Roles', 'group' => 'Roles'],
            ['name' => 'Edit Roles', 'group' => 'Roles'],
            ['name' => 'Delete Roles', 'group' => 'Roles'],
            // products
            ['name' => 'View Products', 'group' => 'Products'],
            ['name' => 'Create Products', 'group' => 'Products'],
            ['name' => 'Edit Products', 'group' => 'Products'],
            ['name' => 'Delete Products', 'group' => 'Products'],
            // branches
            ['name' => 'View Branches', 'group' => 'Branches'],
            ['name' => 'Create Branches', 'group' => 'Branches'],
            ['name' => 'Edit Branches', 'group' => 'Branches'],
            // categories
            ['name' => 'View Categories', 'group' => 'Categories'],
            ['name' => 'Create Categories', 'group' => 'Categories'],
            ['name' => 'Edit Categories', 'group' => 'Categories'],
            ['name' => 'Delete Categories', 'group' => 'Categories'],
            // units
            ['name' => 'View Units', 'group' => 'Units'],
            ['name' => 'Create Units', 'group' => 'Units'],
            ['name' => 'Edit Units', 'group' => 'Units'],
            ['name' => 'Delete Units', 'group' => 'Units'],
            // purchases
            ['name' => 'Create Purchases', 'group' => 'Purchases'],
            ['name' => 'View Purchases', 'group' => 'Purchases'],
            ['name' => 'Edit Purchases', 'group' => 'Purchases'],
            ['name' => 'Delete Purchases', 'group' => 'Purchases'],
            // sales
            ['name' => 'Create Sales', 'group' => 'Sales'],
            ['name' => 'View Sales', 'group' => 'Sales'],
            ['name' => 'Edit Sales', 'group' => 'Sales'],
            ['name' => 'Delete Sales', 'group' => 'Sales'],
            // accounts
            ['name' => 'Create Accounts', 'group' => 'Accounts'],
            ['name' => 'Create Business Accounts', 'group' => 'Accounts'],
            ['name' => 'Create Vendor Accounts', 'group' => 'Accounts'],
            ['name' => 'Create Customer Accounts', 'group' => 'Accounts'],
            ['name' => 'Edit Accounts', 'group' => 'Accounts'],
            ['name' => 'View Business Accounts', 'group' => 'Accounts'],
            ['name' => 'View Vendor Accounts', 'group' => 'Accounts'],
            ['name' => 'View Customer Accounts', 'group' => 'Accounts'],
            // stocks
            ['name' => 'View Stocks', 'group' => 'Stocks'],
            ['name' => 'Transfer Stocks', 'group' => 'Stocks'],
            ['name' => 'Delete Transfer Stocks', 'group' => 'Stocks'],
            ['name' => 'Stock Adjustments', 'group' => 'Stocks'],
            ['name' => 'Delete Stock Adjustments', 'group' => 'Stocks'],
            // expenses
            ['name' => 'Create Expenses', 'group' => 'Expenses'],
            ['name' => 'Delete Expenses', 'group' => 'Expenses'],
            ['name' => 'Create Expense Categories', 'group' => 'Expenses'],
            ['name' => 'Edit Expense Categories', 'group' => 'Expenses'],
            // transfers
            ['name' => 'Transfers', 'group' => 'Transfers'],
            ['name' => 'Delete Transfers', 'group' => 'Transfers'],
            // account adjustments
            ['name' => 'Account Adjustments', 'group' => 'Account Adjustments'],
            ['name' => 'Delete Account Adjustments', 'group' => 'Account Adjustments'],
            // receivings
            ['name' => 'Receivings', 'group' => 'Receivings'],
            ['name' => 'Delete Receivings', 'group' => 'Receivings'],
            // payments
            ['name' => 'Payments', 'group' => 'Payments'],
            ['name' => 'Delete Payments', 'group' => 'Payments'],
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
