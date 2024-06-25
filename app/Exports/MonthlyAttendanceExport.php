<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class MonthlyAttendanceExport implements FromView, WithStyles
{
    protected $user;
    protected $monthlyAttendance;

    public function __construct(User $user, $monthlyAttendance)
    {
        $this->user = $user;
        $this->monthlyAttendance = $monthlyAttendance;
    }

    public function view(): View
    {
        return view('exports.monthly_attendance', [
            'user' => $this->user,
            'monthlyAttendance' => $this->monthlyAttendance,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = Carbon::createFromDate(now()->year, now()->month, 1)->endOfMonth()->day + 1;
        $headerRange = 'A1:' . $this->getColumnName($lastColumn) . '1';
        $styleArray = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '808080'],
            ],
        ];
        $sheet->getStyle($headerRange)->applyFromArray($styleArray);

        // Apply styles for weekend columns and all other cells
        for ($i = 2; $i <= Carbon::createFromDate(now()->year, now()->month, 1)->endOfMonth()->day + 1; $i++) {
            $date = Carbon::createFromDate(now()->year, now()->month, $i - 1);
            $column = $this->getColumnName($i);
            if ($date->isWeekend()) {
                // $sheet->getStyle($column . '1')->applyFromArray([
                //     'fill' => [
                //         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                //         'startColor' => ['argb' => 'FFCCCC'],
                //     ],
                //     'alignment' => [
                //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                //         'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                //         'wrapText' => true,
                //     ],
                // ]);
                // $sheet->getStyle($column . '2:' . $column . '1000')->applyFromArray([
                $sheet->getStyle($column . '2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF0000'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
            } else {
                // $sheet->getStyle($column . '2:' . $column . '1000')->applyFromArray([
                $sheet->getStyle($column . '2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
            }
        }

        // Apply center alignment for all cells
        $sheet->getStyle('A1:' . $this->getColumnName($lastColumn) . '1000')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        return [];
    }

    private function getColumnName($index)
    {
        $letters = '';
        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $letters = chr($mod + 65) . $letters;
            $index = intval(($index - $mod) / 26);
        }
        return $letters;
    }
}
