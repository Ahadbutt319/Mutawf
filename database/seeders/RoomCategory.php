<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomCategory extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('room_categories')->insert([
             ['name'=>'A+',
            'luxuries'=>'Swimming Pool,Two Washrooms,Led 44 inches,Double Bed',
            'price'=>'200$'],
            ['name'=>'A',
            'luxuries'=>'Two Washrooms,Led 44 inches,Single Bed',
            'price'=>'150$'],
            ['name'=>'B+',
            'luxuries'=>'Double Bed',
            'price'=>'100$'],
        ]);
    }
}
