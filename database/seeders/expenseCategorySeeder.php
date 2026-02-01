<?php

namespace Database\Seeders;

use App\Models\expenseCategories;
use Illuminate\Database\Seeder;

class expenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cats = [
            ['name' => 'Transport'],
            ['name' => 'Labor'],
        ];

        expenseCategories::insert($cats);
    }
}
