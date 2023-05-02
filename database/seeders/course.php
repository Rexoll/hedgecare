<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class course extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course')->insert([
            [
                'user_id' => 1,
                'course' => 'english',
                'price' => 300,
            ],
            [
                'user_id' => 2,
                'course' => 'mathematics',
                'price' => 300,
            ],
            [
                'user_id' => 3,
                'course' => 'physics',
                'price' => 300,
            ],
            [
                'user_id' => 4,
                'course' => 'chemistry',
                'price' => 300,
            ],
            [
                'user_id' => 5,
                'course' => 'science',
                'price' => 300,
            ],
            [
                'user_id' => 6,
                'course' => 'history',
                'price' => 300,
            ],
            [
                'user_id' => 7,
                'course' => 'geography',
                'price' => 300,
            ],
            [
                'user_id' => 8,
                'course' => 'music',
                'price' => 300,
            ],
            [
                'user_id' => 9,
                'course' => 'french',
                'price' => 300,
            ],
        ]);
    }
}
