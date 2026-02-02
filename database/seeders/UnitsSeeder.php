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
            ['name' => 'Piece', 'value' => 1],
            ['name' => 'Dozen', 'value' => 12],
            ['name' => 'Box', 'value' => 24],
            ['name' => 'Kg', 'value' => 1000],
            ['name' => 'Packet', 'value' => 1],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
