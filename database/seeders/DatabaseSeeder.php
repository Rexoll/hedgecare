<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\rentAfriendAdditionalService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            adminSeeder::class,
            SkillSeeder::class,
            HousekeepingCategorySeeder::class,
            HousekeepingAdditionalServiceSeeder::class,
            rentAfriendCategorySeeder::class,
            rentAfriendAdditionalServiceSeeder::class,
            maintenanceCategorySeeder::class,
            maintenanceAdditionalServiceSeeder::class,
        ]);
    }
}
