<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HousekeepingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\HousekeepingCategory::insert([
            [
                "name" => "Housekeeping",
                "thumbnail" => env("APP_URL") . "/storage/images/dummy_housekeeping_category.svg",
            ],
            [
                "name" => "Groundkeeping",
                "thumbnail" => env("APP_URL") . "/storage/images/dummy_housekeeping_category.svg",
            ],
        ]);
    }
}