<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skills')->insert([
            [
                'name' => 'French',
                // 'thumbnail' => env("APP_URL") . '/storage/images/dummy_skill_french.png',
            ],
            [
                'name' => 'Physics',
                // 'thumbnail' => env("APP_URL") . '/storage/images/dummy_skill_physics.svg',
            ],
            [
                'name' => 'Math',
                // 'thumbnail' => env("APP_URL") . '/storage/images/dummy_skill_math.svg',
            ],
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
            [
                "name" => "Gardening",
            ],
            [
                "name" => "Lawn mowing",
            ],
            [
                "name" => "Hedging & Pruning",
            ],
            [
                "name" => "Weeding",
            ],
            [
                "name" => "Planting",
            ],
            [
                "name" => "Snow removal",
            ],
            [
                "name" => "Salting driveway and walkways",
            ],
            [
                "name" => "Raking leaves",
            ],
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
            [
                "name" => "Gardening",
            ],
            [
                "name" => "Lawn mowing",
            ],
            [
                "name" => "Hedging & Pruning",
            ],
            [
                "name" => "Weeding",
            ],
            [
                "name" => "Planting",
            ],
            [
                "name" => "Snow removal",
            ],
            [
                "name" => "Salting driveway and walkways",
            ],
            [
                "name" => "Raking leaves",
            ],
            [
                'name' => 'Plumbing',
            ],
            [
                'name' => 'Heating & Cooling (HVAC)',
            ],
            [
                'name' => 'Applicance Installation',
            ],
            [
                'name' => 'Organizing, Decluttering & Packing',
            ],
            [
                'name' => 'Pest Controll',
            ],
            [
                'name' => 'Cabinet Installation',
            ],
            [
                'name' => 'Smart Home Install',
            ],
            [
                'name' => 'Window Cleaning',
            ],
            [
                'name' => 'Junk Cleaning',
            ],
            [
                'name' => 'Junk Cleaning',
            ],
            [
                'name' => 'Junk Cleaning',
            ],
            [
                'name' => 'Decks And Fences',
            ],
            [
                'name' => 'Electrical',
            ],
            [
                'name' => 'Roofing',
            ],
            [
                'name' => 'Flooring',
            ],
            [
                'name' => 'Painting',
            ],
            [
                'name' => 'Sprinkler Winterization',
            ],
            [
                'name' => 'Washroom Renovations',
            ],
            [
                'name' => 'Pool Services',
            ],
            [
                'name' => 'Furniture Assembly',
            ],
            [
                'name' => 'Duct Cleaning',
            ],
        ]);
    }
}
