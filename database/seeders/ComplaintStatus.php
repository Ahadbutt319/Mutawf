<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ComplaintStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('complaint_statuses')->insert([
            ['name' => 'in progress'],
            ['name' => 'rejected'],
            ['name' => 'pending'],
            ['name' => 'resolved'],
            ['name' => 'Closed'],

        ]);
    }
}
