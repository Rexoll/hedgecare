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
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'housekeeping',
            ],
            [
                'user_id' => 2,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'housekeeping',
            ],
            [
                'user_id' => 3,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'housekeeping',
            ],
            [
                'user_id' => 4,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'tutoring',
            ],
            [
                'user_id' => 5,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'tutoring',
            ],
            [
                'user_id' => 6,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'tutoring',
            ],
            [
                'user_id' => 7,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'rentafriend',
            ],
            [
                'user_id' => 8,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'rentafriend',
            ],
            [
                'user_id' => 9,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'rentafriend',
            ],
            [
                'user_id' => 10,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'other',
            ],
            [
                'user_id' => 11,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'other',
            ],
            [
                'user_id' => 12,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'other',
            ],
        ]);
    }
}