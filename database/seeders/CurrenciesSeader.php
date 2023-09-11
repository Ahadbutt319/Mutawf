<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CurrenciesSeader extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->insert([
            ['abbr' => 'USD' , 'name' => 'United States Dollar', 'icon' => '$'],
        ]);
    }
}
