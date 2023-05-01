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
                "category_id" => 1,
                "name" => "Floor cleaning",
            ],
            [
                "category_id" => 1,
                "name" => "Window cleaning",
            ],
            [
                "category_id" => 1,
                "name" => "Laundry",
            ],
            [
                "category_id" => 1,
                "name" => "Ironing",
            ],
            [
                "category_id" => 1,
                "name" => "Refrigerator cleaning",
            ],
            [
                "category_id" => 1,
                "name" => "Oven cleaning",
            ],
            [
                "category_id" => 1,
                "name" => "Cabinet cleaning",
            ],
            [
                "category_id" => 1,
                "name" => "Bed changing",
            ],
            [
                "category_id" => 1,
                "name" => "Carpet cleaning",
            ],
            [
                "category_id" => 1,
                "name" => "Dish washing",
            ],
            [
                "category_id" => 1,
                "name" => "Furniture cleaning",
            ],
            [
                "category_id" => 1,
                "name" => "Wall cleaning",
            ],
            [
                "category_id" => 1,
                "name" => "Plant watering",
            ],
            [
                "category_id" => 1,
                "name" => "Basement cleaning",
            ],
            [
                "category_id" => 1,
                "name" => "Grocery shopping",
            ],
            [
                "category_id" => 1,
                "name" => "Clean up after pets",
            ],
            [
                "category_id" => 1,
                "name" => "House sitting",
            ],
            [
                "category_id" => 1,
                "name" => "Attic cleaning",
            ],
            [
                "category_id" => 1,
                "name" => "Gutter cleaning",
            ],
            [
                "category_id" => 2,
                "name" => "Gardening",
            ],
            [
                "category_id" => 2,
                "name" => "Lawn mowing",
            ],
            [
                "category_id" => 2,
                "name" => "Hedging & Pruning",
            ],
            [
                "category_id" => 2,
                "name" => "Weeding",
            ],
            [
                "category_id" => 2,
                "name" => "Planting",
            ],
            [
                "category_id" => 2,
                "name" => "Snow removal",
            ],
            [
                "category_id" => 2,
                "name" => "Salting driveway and walkways",
            ],
            [
                "category_id" => 2,
                "name" => "Raking leaves",
            ],
        ]);
    }
}