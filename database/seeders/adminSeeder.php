<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class adminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'first_name' => 'Admin',
                'last_name' => 'HedgeCare',
                'email' => 'admin@hedgecare.ca',
                'email_verified_at' => now(),
                'phone_number' => '+6289655376612',
                'role' => 'admin',
                'password' => Hash::make("Masukdeh12"),
            ],
        ]);
    }
}
