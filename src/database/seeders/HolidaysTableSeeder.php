<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class HolidaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('holidays')->insert([
            ['title' => 'Tết Dương lịch', 'start' => '2024-01-01'],
            ['title' => 'Giỗ Tổ Hùng Vương', 'start' => '2024-04-18'],
            ['title' => 'Ngày Giải phóng miền Nam', 'start' => '2024-04-30'],
            ['title' => 'Ngày Quốc tế Lao động', 'start' => '2024-05-01'],
            ['title' => 'Ngày Quốc khánh', 'start' => '2024-09-02'],
        ]);
    }
}
