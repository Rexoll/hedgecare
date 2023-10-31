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
        $skill_id = [];
        for ($i = 31; $i <= 48; $i++) {
            array_push($skill_id, ['category_id' => 1, 'skill_id' => $i]);
        }
        DB::table('maintenance_additional_services')->insert($skill_id);
    }
}
