<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ImageCategoy extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('image_categories')->insert([
            ['image_type' => 'Hotel'],
            ['image_type' => 'Package'],
            ['image_type' => 'Room'],
            ['image_type' => 'Visa'],
            ['image_type' => 'Transportation'],
            ['image_type' => 'Operator'],
        ]);
    }
}
