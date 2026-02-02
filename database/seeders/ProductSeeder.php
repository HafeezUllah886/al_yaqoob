<?php

namespace Database\Seeders;

use App\Models\Product_units;
use App\Models\Products;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $products = [
            [
                'name' => 'Plastic',
                'category_id' => 1,
            ],
            [
                'name' => 'Rice',
                'category_id' => 2,
            ],
        ];

        foreach ($products as $product) {
            $prod = Products::create($product);
            Product_units::create([
                'product_id' => $prod->id,
                'unit_name' => 'Piece',
                'value' => 1,
                'price' => 150,
            ]);
            Product_units::create([
                'product_id' => $prod->id,
                'unit_name' => 'Kg',
                'value' => 1000,
                'price' => 300,
            ]);
        }
    }
}
