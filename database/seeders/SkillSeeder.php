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
                'thumbnail' => '/storage/images/dummy_skill_french.png',
            ],
            [
                'name' => 'Physics',
                'thumbnail' => '/storage/images/dummy_skill_physics.svg',
            ],
            [
                'name' => 'Math',
                'thumbnail' => '/storage/images/dummy_skill_math.svg',
            ],
        ]);
    }
}