<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user_owner = User::create([
            'username' => 'owner',
            'name' => 'Owner',
            'email' => 'owner@yasaka.com',
            'password'=> bcrypt('owner')
        ]);

        $user_owner->assignRole('owner');

        $user_cashier = User::create([
            'username' => 'cashier',
            'name' => 'Cashier Crew',
            'email' => 'cashier@yasaka.com',
            'password'=> bcrypt('cashier')
        ]);

        $user_cashier->assignRole('cashier');

    }
}
