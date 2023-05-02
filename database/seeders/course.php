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
                'course' => 'english',
                'price' => 300,
            ],
            [
                'course' => 'mathematics',
                'price' => 300,
            ],
            [
                'course' => 'physics',
                'price' => 300,
            ],
            [
                'course' => 'chemistry',
                'price' => 300,
            ],
            [
                'course' => 'science',
                'price' => 300,
            ],
            [
                'course' => 'history',
                'price' => 300,
            ],
            [
                'course' => 'geography',
                'price' => 300,
            ],
            [
                'course' => 'music',
                'price' => 300,
            ],
            [
                'course' => 'french',
                'price' => 300,
            ],
        ]);
    }
}
