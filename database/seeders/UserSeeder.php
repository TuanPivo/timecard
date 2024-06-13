<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 2; $i++) {

            DB::table('users')->insert([
                'name' => 'tuan',
                'email' => 'tuan' . $i . '@gmail.com',
                'password' => Hash::make('12345678@'),
                'role' => 1
            ]);
            DB::table('users')->insert([
                'name' => 'admin',
                'email' => 'admin' . $i . '@gmail.com',
                'password' => Hash::make('12345678@'),
                'role' => 0
            ]);
        }
    }
}
