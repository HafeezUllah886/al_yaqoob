<?php

namespace Database\Seeders;

use App\Models\Product_units;
use App\Models\products;
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
            $prod = products::create($product);
            Product_units::create([
                'product_id' => $prod->id,
                'unit_name' => 'Piece',
                'value' => 1,
                'price' => 0,
            ]);
            Product_units::create([
                'product_id' => $prod->id,
                'unit_name' => 'Kg',
                'value' => 1,
                'price' => 0,
            ]);
        }
    }
}
