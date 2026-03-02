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
            // Dashboard
            ['name' => 'Sales', 'group' => 'Dashboard'],
            ['name' => 'Purchases', 'group' => 'Dashboard'],
            ['name' => 'Expenses', 'group' => 'Dashboard'],
            ['name' => 'Non-Business Expenses', 'group' => 'Dashboard'],
            ['name' => 'Stocks Value', 'group' => 'Dashboard'],
            ['name' => 'Customer Balance', 'group' => 'Dashboard'],
            ['name' => 'Vendor Balance', 'group' => 'Dashboard'],
            ['name' => 'Business Balance', 'group' => 'Dashboard'],
            ['name' => 'Branch Balance', 'group' => 'Dashboard'],
            ['name' => 'Sales Vs Expenses', 'group' => 'Dashboard'],
            // accounts
            ['name' => 'Create Accounts', 'group' => 'Accounts'],
            ['name' => 'Create Business Accounts', 'group' => 'Accounts'],
            ['name' => 'Create Vendor Accounts', 'group' => 'Accounts'],
            ['name' => 'Create Customer Accounts', 'group' => 'Accounts'],
            ['name' => 'Create Transporter Accounts', 'group' => 'Accounts'],
            ['name' => 'Edit Accounts', 'group' => 'Accounts'],
            ['name' => 'View Business Accounts', 'group' => 'Accounts'],
            ['name' => 'View Vendor Accounts', 'group' => 'Accounts'],
            ['name' => 'View Customer Accounts', 'group' => 'Accounts'],
            ['name' => 'View Transporter Accounts', 'group' => 'Accounts'],
            // reports
            ['name' => 'Sales Report', 'group' => 'Reports'],
            ['name' => 'Purchases Report', 'group' => 'Reports'],
            ['name' => 'Stocks Report', 'group' => 'Reports'],
            ['name' => 'Expenses Report', 'group' => 'Reports'],
            ['name' => 'Profit and Loss Report', 'group' => 'Reports'],
            ['name' => 'Balance Sheet Report', 'group' => 'Reports'],
            ['name' => 'Branch Balance Sheet Report', 'group' => 'Reports'],
            ['name' => 'Non-Business Expenses Report', 'group' => 'Reports'],
            // stocks
            ['name' => 'View Stocks', 'group' => 'Stocks'],
            ['name' => 'Transfer Stocks', 'group' => 'Stocks'],
            ['name' => 'Delete Transfer Stocks', 'group' => 'Stocks'],
            ['name' => 'Stock Adjustments', 'group' => 'Stocks'],
            ['name' => 'Delete Stock Adjustments', 'group' => 'Stocks'],
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
            // expenses
            ['name' => 'Create Expenses', 'group' => 'Expenses'],
            ['name' => 'Delete Expenses', 'group' => 'Expenses'],
            ['name' => 'Create Expense Categories', 'group' => 'Expenses'],
            ['name' => 'Edit Expense Categories', 'group' => 'Expenses'],
            // non-business expenses
            ['name' => 'Create Non-Business Expenses', 'group' => 'Expenses'],
            ['name' => 'Delete Non-Business Expenses', 'group' => 'Expenses'],
            ['name' => 'Create Non-Business Expense Categories', 'group' => 'Expenses'],
            ['name' => 'Edit Non-Business Expense Categories', 'group' => 'Expenses'],
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
