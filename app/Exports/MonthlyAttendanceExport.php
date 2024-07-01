<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Holiday;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyAttendanceExport implements FromView, WithStyles
{
    protected $user;
    protected $monthlyAttendance;
    protected $selectedMonth;
    protected $selectedYear;
    protected $holidays;

    public function __construct(User $user, $monthlyAttendance, $selectedMonth, $selectedYear)
    {
        $this->user = $user;
        $this->monthlyAttendance = $monthlyAttendance;
        $this->selectedMonth = $selectedMonth;
        $this->selectedYear = $selectedYear;

        // Fetch holidays for the specified month and year
        $this->holidays = Holiday::whereYear('start', $selectedYear)
                            ->whereMonth('start', $selectedMonth)
                            ->get()
                            ->pluck('start')
                            ->map(function ($date) {
                                return Carbon::parse($date)->day;
                            })
                            ->toArray();
    }

    public function view(): View
    {
        return view('exports.monthly_attendance', [
            'user' => $this->user,
            'monthlyAttendance' => $this->monthlyAttendance,
            'selectedMonth' => $this->selectedMonth,
            'selectedYear' => $this->selectedYear,
            'holidays' => $this->holidays,
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
                'startColor' => ['argb' => '808080'],
            ],
        ];
        $sheet->getStyle($headerRange)->applyFromArray($styleArray);

        for ($i = 2; $i <= Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->endOfMonth()->day + 1; $i++) {
            $date = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, $i - 1);
            $column = $this->getColumnName($i);

            if ($date->isWeekend() || in_array($date->day, $this->holidays)) {
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

            $richText = new RichText();

            if (isset($attendance['check_in'])) {
                $status = $attendance['check_in']['status'];
                $color = $this->getColorForStatus($status);

                // $checkInText = $richText->createTextRun("Checkin: {$attendance['check_in']['date']}\n");
                $checkInText = $richText->createTextRun("{$attendance['check_in']['date']}\n");
                $checkInText->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($color));
            }

            if (isset($attendance['check_out'])) {
                $status = $attendance['check_out']['status'];
                $color = $this->getColorForStatus($status);

                // $checkOutText = $richText->createTextRun("Checkout: {$attendance['check_out']['date']}");
                $checkOutText = $richText->createTextRun("{$attendance['check_out']['date']}");
                $checkOutText->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($color));
            }

            $sheet->setCellValue($column . $row, $richText);
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
                return '000000';
            case 'reject':
                return '000000';
            case 'success':
                return '000000';
            default:
                return '000000';
        }
    }
}