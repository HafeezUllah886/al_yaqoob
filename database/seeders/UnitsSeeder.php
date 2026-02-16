<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Kg', 'value' => 1],
            ['name' => 'Bag', 'value' => 25],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
