<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => Hash::make('admin'),
            ],
            [
                'name' => 'Operator',
                'email' => 'operator@email.com',
                'password' => Hash::make('operator'),
            ],
        ];
        foreach ($users as $user) {
            User::create($user);
        }

        $admin = User::where('email', 'admin@email.com')->first();
        $admin->assignRole('Admin');

        $operator = User::where('email', 'operator@email.com')->first();
        $operator->assignRole('Operator');
    }
}
