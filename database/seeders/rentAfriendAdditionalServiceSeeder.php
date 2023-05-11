<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class rentAfriendAdditionalServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rentAfriend_additional_services')->insert([

            [
                "category_id" => 1,
                "name" => "Wingman/Wingwoman",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Playing Sports",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Sightseeing",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Dinner",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Hiking",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "FamilyFunctions",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Gym friend/Workout Partner",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Going to a bar",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Walking buddy",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Business events",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Biking",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Exploring the city",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Social/emotional support",
                "created_at" => now()
            ],
            [
                "category_id" => 1,
                "name" => "Other",
                "created_at" => now()
            ],
            [
                "category_id" => 2,
                "name" => "Phone friend",
                "created_at" => now()
            ],
            [
                "category_id" => 2,
                "name" => "Facetime/Vidiocall",
                "created_at" => now()
            ],
            [
                "category_id" => 2,
                "name" => "Social/emotional support",
                "created_at" => now()
            ],
            [
                "category_id" => 2,
                "name" => "Other",
                "created_at" => now()
            ],
        ]);
    }
}
