<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'first_name' => 'Mulia',
                'last_name' => 'Firmansyah',
                'email' => 'mulia@gmail.com',
                'phone_number' => '+6289655376610',
                'role' => 'provider',
            ],
            [
                'first_name' => 'roy',
                'last_name' => 'marteen',
                'email' => 'marteen@gmail.com',
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
            ],
            [
                'first_name' => 'jack',
                'last_name' => 'zoro',
                'email' => 'zoro@gmail.com',
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
            ],
            [
                'first_name' => 'pablo',
                'last_name' => 'konvici',
                'email' => 'konvici@gmail.com',
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
            ],
            [
                'first_name' => 'spesfi',
                'last_name' => 'okla',
                'email' => 'okla@gmail.com',
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
            ],
            [
                'first_name' => 'wodie',
                'last_name' => 'hodie',
                'email' => 'hodie@gmail.com',
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
            ],
            [
                'first_name' => 'coffso',
                'last_name' => 'toreto',
                'email' => 'toreto@gmail.com',
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
            ],
            [
                'first_name' => 'michele',
                'last_name' => 'roundi',
                'email' => 'roundi@gmail.com',
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
            ],
            [
                'first_name' => 'dina',
                'last_name' => 'murni',
                'email' => 'dina@gmail.com',
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
            ],
            [
                'first_name' => 'steve',
                'last_name' => 'jobs',
                'email' => 'steve@gmail.com',
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
            ],
            [
                'first_name' => 'mark',
                'last_name' => 'zuckerberb',
                'email' => 'mark@gmail.com',
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
            ],
            [
                'first_name' => 'jeff',
                'last_name' => 'bezos',
                'email' => 'jeff@gmail.com',
                'phone_number' => '+62' . rand(10000000000, 99999999999),
                'role' => 'provider',
            ],
        ]);
    }
}