<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Các ngày nghỉ lễ ở Việt Nam
        $holidays = [
            ['title' => 'Tết Dương Lịch', 'start' => '2024-01-01', 'end' => '2024-01-01', 'color' => '#FF0000'],
            ['title' => 'Tết Nguyên Đán', 'start' => '2024-02-08', 'end' => '2024-02-12', 'color' => '#FF5733'],
            ['title' => 'Giỗ Tổ Hùng Vương', 'start' => '2024-04-18', 'end' => '2024-04-18', 'color' => '#C70039'],
            ['title' => 'Ngày Giải Phóng Miền Nam', 'start' => '2024-04-30', 'end' => '2024-04-30', 'color' => '#900C3F'],
            ['title' => 'Ngày Quốc tế Lao Động', 'start' => '2024-05-01', 'end' => '2024-05-01', 'color' => '#FFC300'],
            ['title' => 'Quốc khánh', 'start' => '2024-09-02', 'end' => '2024-09-03', 'color' => '#DAF7A6'],
        ];

        // Insert dữ liệu vào bảng holidays
        DB::table('holidays')->insert($holidays);
    }
}
