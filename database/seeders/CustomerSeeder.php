<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Customer',
            'last_name' => 'User',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => config('constants.roles.customer'),
        ]);

        $user->customer()->create([
            'user_id' => $user->id,
            'phone' => '254712121212',
        ]);
    }
}
