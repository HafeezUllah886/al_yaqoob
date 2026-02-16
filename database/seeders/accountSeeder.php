<?php

namespace Database\Seeders;

use App\Models\accounts;
use Illuminate\Database\Seeder;

class accountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        accounts::create(
            [
                'title' => 'Main Branch Cash Account',
                'type' => 'Business',
                'category' => 'Cash',
                'branch_id' => 1,
            ]
        );
        accounts::create(
            [
                'title' => 'Branch 2 Cash Account',
                'type' => 'Business',
                'category' => 'Cash',
                'branch_id' => 2,
            ]
        );

        accounts::create(
            [
                'title' => 'Walk-In Customer',
                'type' => 'Customer',
                'branch_id' => 1,
            ]
        );

        accounts::create(
            [
                'title' => 'Walk-In Vendor',
                'type' => 'Vendor',
                'branch_id' => 1,
            ]
        );
    }
}
