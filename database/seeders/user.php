<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class user extends Seeder
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
            [
                'first_name' => 'Mulia',
                'last_name' => 'Firmansyah',
                'email' => 'mulia@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+6289655376610',
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
            [
                'first_name' => 'roy',
                'last_name' => 'marteen',
                'email' => 'marteen@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
            [
                'first_name' => 'jack',
                'last_name' => 'zoro',
                'email' => 'zoro@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
            [
                'first_name' => 'pablo',
                'last_name' => 'konvici',
                'email' => 'konvici@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
            [
                'first_name' => 'spesfi',
                'last_name' => 'okla',
                'email' => 'okla@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
            [
                'first_name' => 'wodie',
                'last_name' => 'hodie',
                'email' => 'hodie@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
            [
                'first_name' => 'coffso',
                'last_name' => 'toreto',
                'email' => 'toreto@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
            [
                'first_name' => 'michele',
                'last_name' => 'roundi',
                'email' => 'roundi@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
            [
                'first_name' => 'dina',
                'last_name' => 'murni',
                'email' => 'dina@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
            [
                'first_name' => 'steve',
                'last_name' => 'jobs',
                'email' => 'steve@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
            [
                'first_name' => 'mark',
                'last_name' => 'zuckerberb',
                'email' => 'mark@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
            [
                'first_name' => 'jeff',
                'last_name' => 'bezos',
                'email' => 'jeff@gmail.com',
                'email_verified_at' => now(),
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
                'password' => Hash::make("Masukdeh12"),
            ],
        ]);
    }
}
