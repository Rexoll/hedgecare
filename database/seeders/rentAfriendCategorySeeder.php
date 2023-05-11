<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class rentAfriendCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rentAfriend_categories')->insert([
            [
                "name" => "Local friend",
                "thumbnail" => env("APP_URL") . "/storage/images/dummy_housekeeping_category.svg",
            ],
            [
                "name" => "Virtual friend",
                "thumbnail" => env("APP_URL") . "/storage/images/dummy_housekeeping_category.svg",
            ],
        ]);
    }
}
