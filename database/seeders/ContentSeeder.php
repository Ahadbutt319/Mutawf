<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contents')->insert([
            [
                'content'=>'
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vehicula mi eu mauris varius, at fermentum nulla cursus. Suspendisse potenti. Phasellus luctus dolor in dui tincidunt, eu sagittis velit ultricies. Maecenas vel quam at sapien tincidunt dapibus. Curabitur eu ex eu nulla aliquam ultricies a ac augue. Nullam bibendum, felis ac efficitur tincidunt, urna elit rhoncus turpis, nec vulputate justo purus at nunc.',
                'user_id'=>1,
                'content_id'=>1
            ],
            [
                'content'=>'
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vehicula mi eu mauris varius, at fermentum nulla cursus. Suspendisse potenti. Phasellus luctus dolor in dui tincidunt, eu sagittis velit ultricies. Maecenas vel quam at sapien tincidunt dapibus. Curabitur eu ex eu nulla aliquam ultricies a ac augue. Nullam bibendum, felis ac efficitur tincidunt, urna elit rhoncus turpis, nec vulputate justo purus at nunc.',
            'user_id'=>1,
            'content_id'=>2
            ],
            [
                'content'=>'
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vehicula mi eu mauris varius, at fermentum nulla cursus. Suspendisse potenti. Phasellus luctus dolor in dui tincidunt, eu sagittis velit ultricies. Maecenas vel quam at sapien tincidunt dapibus. Curabitur eu ex eu nulla aliquam ultricies a ac augue. Nullam bibendum, felis ac efficitur tincidunt, urna elit rhoncus turpis, nec vulputate justo purus at nunc.',
                'user_id'=>1,
                'content_id'=>3
            ],
            [
                'content'=>'
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vehicula mi eu mauris varius, at fermentum nulla cursus. Suspendisse potenti. Phasellus luctus dolor in dui tincidunt, eu sagittis velit ultricies. Maecenas vel quam at sapien tincidunt dapibus. Curabitur eu ex eu nulla aliquam ultricies a ac augue. Nullam bibendum, felis ac efficitur tincidunt, urna elit rhoncus turpis, nec vulputate justo purus at nunc.',
                'user_id'=>1,
                'content_id'=>4
            ],
            [
                'content'=>'
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vehicula mi eu mauris varius, at fermentum nulla cursus. Suspendisse potenti. Phasellus luctus dolor in dui tincidunt, eu sagittis velit ultricies. Maecenas vel quam at sapien tincidunt dapibus. Curabitur eu ex eu nulla aliquam ultricies a ac augue. Nullam bibendum, felis ac efficitur tincidunt, urna elit rhoncus turpis, nec vulputate justo purus at nunc.',
                'user_id'=>1,
                'content_id'=>5
            ],
            [
                'content'=>'
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vehicula mi eu mauris varius, at fermentum nulla cursus. Suspendisse potenti. Phasellus luctus dolor in dui tincidunt, eu sagittis velit ultricies. Maecenas vel quam at sapien tincidunt dapibus. Curabitur eu ex eu nulla aliquam ultricies a ac augue. Nullam bibendum, felis ac efficitur tincidunt, urna elit rhoncus turpis, nec vulputate justo purus at nunc.',
                'user_id'=>1,
                'content_id'=>6
            ],
            [
                'content'=>'
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vehicula mi eu mauris varius, at fermentum nulla cursus. Suspendisse potenti. Phasellus luctus dolor in dui tincidunt, eu sagittis velit ultricies. Maecenas vel quam at sapien tincidunt dapibus. Curabitur eu ex eu nulla aliquam ultricies a ac augue. Nullam bibendum, felis ac efficitur tincidunt, urna elit rhoncus turpis, nec vulputate justo purus at nunc.',
                'user_id'=>1,
                'content_id'=>7
            ],
            [
                'content'=>'
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vehicula mi eu mauris varius, at fermentum nulla cursus. Suspendisse potenti. Phasellus luctus dolor in dui tincidunt, eu sagittis velit ultricies. Maecenas vel quam at sapien tincidunt dapibus. Curabitur eu ex eu nulla aliquam ultricies a ac augue. Nullam bibendum, felis ac efficitur tincidunt, urna elit rhoncus turpis, nec vulputate justo purus at nunc.',
                'user_id'=>1,
                'content_id'=>8            ],

            [
                'content'=>'
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vehicula mi eu mauris varius, at fermentum nulla cursus. Suspendisse potenti. Phasellus luctus dolor in dui tincidunt, eu sagittis velit ultricies. Maecenas vel quam at sapien tincidunt dapibus. Curabitur eu ex eu nulla aliquam ultricies a ac augue. Nullam bibendum, felis ac efficitur tincidunt, urna elit rhoncus turpis, nec vulputate justo purus at nunc.',
                'user_id'=>1,
                'content_id'=>9
            ],

        ]);
    }
}
