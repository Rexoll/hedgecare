<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HousekeepingAdditionalServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\HousekeepingAdditionalService::insert([
            [
                "name" => "Floor cleaning",
            ],
            [
                "name" => "Window cleaning",
            ],
            [
                "name" => "Laundry",
            ],
            [
                "name" => "Ironing",
            ],
            [
                "name" => "Refrigerator cleaning",
            ],
            [
                "name" => "Oven cleaning",
            ],
            [
                "name" => "Cabinet cleaning",
            ],
            [
                "name" => "Bed changing",
            ],
            [
                "name" => "Carpet cleaning",
            ],
            [
                "name" => "Dish washing",
            ],
            [
                "name" => "Furniture cleaning",
            ],
            [
                "name" => "Wall cleaning",
            ],
            [
                "name" => "Plant watering",
            ],
            [
                "name" => "Basement cleaning",
            ],
            [
                "name" => "Grocery shopping",
            ],
            [
                "name" => "Clean up after pets",
            ],
            [
                "name" => "House sitting",
            ],
            [
                "name" => "Attic cleaning",
            ],
            [
                "name" => "Gutter cleaning",
            ],
        ]);
    }
}