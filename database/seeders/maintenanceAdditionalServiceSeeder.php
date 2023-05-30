<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class maintenanceAdditionalServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('maintenance_additional_services')->insert([
            [
                'name' => 'Plumbing',
                'category_id' => 1
            ],
            [
                'name' => 'Heating & Cooling (HVAC)',
                'category_id' => 1
            ],
            [
                'name' => 'Applicance Installation',
                'category_id' => 1
            ],
            [
                'name' => 'Organizing, Decluttering & Packing',
                'category_id' => 1
            ],
            [
                'name' => 'Pest Controll',
                'category_id' => 1
            ],
            [
                'name' => 'Cabinet Installation',
                'category_id' => 1
            ],
            [
                'name' => 'Smart Home Install',
                'category_id' => 1
            ],
            [
                'name' => 'Window Cleaning',
                'category_id' => 1
            ],
            [
                'name' => 'Junk Cleaning',
                'category_id' => 1
            ],
            [
                'name' => 'Junk Cleaning',
                'category_id' => 1
            ],
            [
                'name' => 'Junk Cleaning',
                'category_id' => 1
            ],
            [
                'name' => 'Decks And Fences',
                'category_id' => 1
            ],
            [
                'name' => 'Electrical',
                'category_id' => 1
            ],
            [
                'name' => 'Roofing',
                'category_id' => 1
            ],
            [
                'name' => 'Flooring',
                'category_id' => 1
            ],
            [
                'name' => 'Painting',
                'category_id' => 1
            ],
            [
                'name' => 'Sprinkler Winterization',
                'category_id' => 1
            ],
            [
                'name' => 'Washroom Renovations',
                'category_id' => 1
            ],
            [
                'name' => 'Pool Services',
                'category_id' => 1
            ],
            [
                'name' => 'Furniture Assembly',
                'category_id' => 1
            ],
            [
                'name' => 'Duct Cleaning',
                'category_id' => 1
            ],
            [
                'name' => 'Other',
                'category_id' => 1
            ],
        ]);
    }
}
