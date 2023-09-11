<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class LanguagesSeader extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('languages')->insert([
            ['abbr' => 'en' , 'name' => 'English'],
            ['abbr' => 'ar' , 'name' => 'Arabic'],
        ]);
    }
}
