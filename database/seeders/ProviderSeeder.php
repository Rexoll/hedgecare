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
                'active_days' => 'Tuesday,Wednesday,Thursday,Friday,Saturday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
            [
                'user_id' => 2,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'housekeeping',
                'active_days' => 'Sunday,Monday,Thursday,Friday,Saturday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
            [
                'user_id' => 3,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'housekeeping',
                'active_days' => 'Sunday,Monday,Tuesday,Wednesday,Saturday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
            [
                'user_id' => 4,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'tutoring',
                'active_days' => 'Monday,Tuesday,Wednesday,Thursday,Friday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
            [
                'user_id' => 5,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'tutoring',
                'active_days' => 'Sunday,Wednesday,Thursday,Friday,Saturday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
            [
                'user_id' => 6,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'tutoring',
                'active_days' => 'Sunday,Monday,Tuesday,Friday,Saturday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
            [
                'user_id' => 7,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'rentafriend',
                'active_days' => 'Sunday,Monday,Thursday,Friday,Saturday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
            [
                'user_id' => 8,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'rentafriend',
                'active_days' => 'Sunday,Monday,Tuesday,Wednesday,Saturday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
            [
                'user_id' => 9,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'rentafriend',
                'active_days' => 'Monday,Tuesday,Wednesday,Thursday,Friday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
            [
                'user_id' => 10,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'other',
                'active_days' => 'Sunday,Wednesday,Thursday,Friday,Saturday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
            [
                'user_id' => 11,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'other',
                'active_days' => 'Sunday,Monday,Tuesday,Friday,Saturday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
            [
                'user_id' => 12,
                'thumbnail' => env("APP_URL") . '/storage/images/dummy_person.png',
                'about' => 'Hardworking and focused. I’ve assembled over 100 pieces of furniture and would be happy to help and assemble your new pieces!',
                'price' => mt_rand(200, 1400) / 10,
                'rating' => mt_rand(10, 50) / 10,
                'review' => mt_rand(10, 120),
                'category' => 'other',
                'active_days' => 'Sunday,Monday,Tuesday,Wednesday,Thursday',
                'address' => '8920  xStreet, Vancouver 49859, USA',
                'start_time_available' => mt_rand(0, 12) . ":00:00",
                'end_time_available' => mt_rand(13, 24) . ":00:00",
            ],
        ]);
    }
}