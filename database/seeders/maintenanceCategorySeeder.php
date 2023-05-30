<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class maintenanceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('maintenance_categories')->insert([
            [
                "name" => "Maintenance/repair",
                "thumbnail" => env("APP_URL") . "/storage/images/dummy_housekeeping_category.svg",
            ],
        ]);
    }
}
