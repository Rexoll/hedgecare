<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class maintenanceAdditionalServicesPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prices = [];

        for ($i = 1; $i <= 12; $i++) {
            for ($j = 1; $j <= 22; $j++) {
                $prices = [
                    ...$prices,
                    [
                        'provider_id' => $i,
                        'service_id' => $j,
                        'price' => mt_rand(40, 400) / 10,
                    ],
                ];
            }
        }



        DB::table('maintenance_additional_service_prices')->insert($prices);
    }
}
