<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provider_skill')->insert([
            [
                'provider_id' => 1,
                'skill_id' => 1,
            ],
            [
                'provider_id' => 2,
                'skill_id' => 2,
            ],
            [
                'provider_id' => 3,
                'skill_id' => 3,
            ],
            [
                'provider_id' => 4,
                'skill_id' => 1,
            ],
            [
                'provider_id' => 5,
                'skill_id' => 2,
            ],
            [
                'provider_id' => 6,
                'skill_id' => 3,
            ],
            [
                'provider_id' => 7,
                'skill_id' => 1,
            ],
            [
                'provider_id' => 8,
                'skill_id' => 2,
            ],
            [
                'provider_id' => 9,
                'skill_id' => 3,
            ],
            [
                'provider_id' => 10,
                'skill_id' => 1,
            ],
            [
                'provider_id' => 11,
                'skill_id' => 2,
            ],
            [
                'provider_id' => 12,
                'skill_id' => 3,
            ],
        ]);
    }
}