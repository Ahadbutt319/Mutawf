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
                'name' => 'John Doe',
                'role_id' => 1,
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'country_id' => 1,
                'nationality_country_id' => 1,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone_verified_at' => now()
            ],
            [
                'first_name' => 'Agent Doe',
                'role_id' => 2,
                'email' => 'agent@example.com',
                'phone' => '03467654321',
                'country_id' => 192,
                'nationality_country_id' => 192,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone_verified_at' => now()
            ],
            [
                'first_name' => 'Admin Doe',
                'role_id' => 3,
                'email' => 'admin@example.com',
                'phone' => '03461234567',
                'country_id' => 164,
                'nationality_country_id' => 164,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone_verified_at' => now()
            ],
        ];

        DB::table('users')->insert($users);
    }
}
