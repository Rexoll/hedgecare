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
                "thumbnail" => "/storage/images/dummy_housekeeping_category.svg",
            ],
            [
                "name" => "Groundkeeping",
                "thumbnail" => "/storage/images/dummy_housekeeping_category.svg",
            ],
        ]);
    }
}