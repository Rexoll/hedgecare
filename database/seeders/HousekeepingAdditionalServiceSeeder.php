<?php

namespace Database\Seeders;

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
        $skill_id = [];
        for ($i = 4; $i <= 22; $i++) {
            array_push($skill_id, ['category_id' => 1, 'skill_id' => $i]);
        }
        for ($i = 23; $i <= 30; $i++) {
            array_push($skill_id, ['category_id' => 2, 'skill_id' => $i]);
        }

        \App\Models\HousekeepingAdditionalService::insert($skill_id);
    }
}
