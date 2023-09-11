<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'role_id' => 1,
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'country_id' => 1,
                'nationality_country_id' => 1,
                'password' => Hash::make('password')
            ],
            [
                'first_name' => 'Agent',
                'last_name' => 'Doe',
                'role_id' => 2,
                'email' => 'agent@example.com',
                'phone' => '03467654321',
                'country_id' => 192,
                'nationality_country_id' => 192,
                'password' => Hash::make('password')
            ],
            [
                'first_name' => 'Admin',
                'last_name' => 'Doe',
                'role_id' => 3,
                'email' => 'admin@example.com',
                'phone' => '03461234567',
                'country_id' => 164,
                'nationality_country_id' => 164,
                'password' => Hash::make('password')
            ],
        ];

        DB::table('users')->insert($users);
    }
}
