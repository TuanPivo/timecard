<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyAttendanceExport implements FromView, WithStyles
{
    protected $user;
    protected $monthlyAttendance;
    protected $selectedMonth;
    protected $selectedYear;

    public function __construct(User $user, $monthlyAttendance, $selectedMonth, $selectedYear)
    {
        $this->user = $user;
        $this->monthlyAttendance = $monthlyAttendance;
        $this->selectedMonth = $selectedMonth;
        $this->selectedYear = $selectedYear;
    }

    public function view(): View
    {
        return view('exports.monthly_attendance', [
            'user' => $this->user,
            'monthlyAttendance' => $this->monthlyAttendance,
            'selectedMonth' => $this->selectedMonth,
            'selectedYear' => $this->selectedYear,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->endOfMonth()->day + 1;
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
                'startColor' => ['argb' => '48abf7'],
            ],
        ];
        $sheet->getStyle($headerRange)->applyFromArray($styleArray);

        for ($i = 2; $i <= Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->endOfMonth()->day + 1; $i++) {
            $date = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, $i - 1);
            $column = $this->getColumnName($i);
            if ($date->isWeekend()) {
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
                $sheet->getStyle($column . '2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
            }
        }

        foreach ($this->monthlyAttendance as $day => $attendance) {
            $row = 2; // data starts from row 2
            $column = $this->getColumnName($day + 1);

            if (isset($attendance['check_in'])) {
                $status = $attendance['check_in']['status'];
                $color = $this->getColorForStatus($status);
                $sheet->getStyle($column . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => $color],
                    ],
                ]);
            }

            if (isset($attendance['check_out'])) {
                $status = $attendance['check_out']['status'];
                $color = $this->getColorForStatus($status);
                $sheet->getStyle($column . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => $color],
                    ],
                ]);
            }
        }

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

    private function getColorForStatus($status)
    {
        switch ($status) {
            case 'pending':
                return 'FFFF00'; // yellow
            case 'reject':
                return 'FF0000'; // red
            case 'success':
                return '00FF00'; // green
            default:
                return 'FFFFFF'; // white
        }
    }
}