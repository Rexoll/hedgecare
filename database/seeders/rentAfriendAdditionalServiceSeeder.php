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
        $skill_id = [];
        for ($i = 79; $i <= 90; $i++) {
            array_push($skill_id, ['category_id' => 1, 'skill_id' => $i]);
        }
        for ($i = 91; $i <= 94; $i++) {
            array_push($skill_id, ['category_id' => 2, 'skill_id' => $i]);
        }
        DB::table('rentAfriend_additional_services')->insert($skill_id);
    }
}
