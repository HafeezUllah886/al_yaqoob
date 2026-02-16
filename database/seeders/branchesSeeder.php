<?php

namespace Database\Seeders;

use App\Models\Branches;
use App\Models\User;
use Illuminate\Database\Seeder;

class branchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch = Branches::create([
            'name' => 'Main Branch',
            'address' => 'Main Branch',
            'phone' => '1234567890',
        ]);
        $branch2 = Branches::create([
            'name' => 'Branch 2',
            'address' => 'Branch 2',
            'phone' => '1234567890',
        ]);

        $users = User::all();
        $branch->syncUsers($users->pluck('id')->toArray());
        $branch2->syncUsers($users->pluck('id')->toArray());
    }
}
