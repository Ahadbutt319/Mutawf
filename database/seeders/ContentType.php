<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContentType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('content_types')->insert([
            ['name' => 'aboutus'],
            ['name' => 'Termandcondition'],
            ['name' => 'disclimars'],
            ['name' => 'privacy'],
            ['name' => 'General'],
            ['name' => 'Refunds'],
            ['name' => 'Cancellation by the customer'],
            ['name' => 'Assisted refund'],
            ['name' => 'Special cases'],

        ]);
    }
}
