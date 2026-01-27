<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronics = Category::where('name', 'Electronics')->first();
        $grocery = Category::where('name', 'Grocery')->first();
        $piece = Unit::where('name', 'Piece')->first();
        $kg = Unit::where('name', 'Kg')->first();

        $products = [
            [
                'name' => 'iPhone 15',
                'code' => 'P001',
                'category_id' => $electronics ? $electronics->id : null,
                'unit_id' => $piece ? $piece->id : 1,
                'price' => 999.99,
            ],
            [
                'name' => 'Samsung TV',
                'code' => 'P002',
                'category_id' => $electronics ? $electronics->id : null,
                'unit_id' => $piece ? $piece->id : 1,
                'price' => 599.50,
            ],
            [
                'name' => 'Rice',
                'code' => 'P003',
                'category_id' => $grocery ? $grocery->id : null,
                'unit_id' => $kg ? $kg->id : 1,
                'price' => 2.50,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
