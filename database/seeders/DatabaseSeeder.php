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
            user::class,
            SkillSeeder::class,
            HousekeepingCategorySeeder::class,
            HousekeepingAdditionalServiceSeeder::class,
            course::class,
            ProviderSeeder::class,
            ProviderSkillSeeder::class,
            rentAfriendCategorySeeder::class,
            maintenanceCategorySeeder::class,
            maintenanceAdditionalServiceSeeder::class,
        ]);
    }
}
