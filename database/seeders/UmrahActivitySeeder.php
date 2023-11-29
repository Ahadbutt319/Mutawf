<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UmrahActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('umrah_activities')->insert([
           [ 'locations' => json_encode([
            'Gazwa-e-Khandak',
            'Uhad-mountain',
            'Qouba-Mosque',
        ]),
            'title' => 'Madina Ziarat',
            'vehicle' => 'luxury car',
            'price' => 304.00,]
       ]);
    }
}
