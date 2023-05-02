<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('providers')->insert([
            [
                'user_id' => 1,
                'thumbnail' => '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => 129.50,
                'rating' => 4.5,
                'review' => 120,
                'category' => 'housekeeping',
            ],
            [
                'user_id' => 2,
                'thumbnail' => '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => 129.50,
                'rating' => 4.5,
                'review' => 120,
                'category' => 'housekeeping',
            ],
            [
                'user_id' => 3,
                'thumbnail' => '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => 129.50,
                'rating' => 4.5,
                'review' => 120,
                'category' => 'housekeeping',
            ],
        ]);
    }
}