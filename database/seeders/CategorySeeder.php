<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Grocery'],
            ['name' => 'Clothing'],
            ['name' => 'Furniture'],
            ['name' => 'Beauty'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
