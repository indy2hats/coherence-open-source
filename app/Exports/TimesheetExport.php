<?php

namespace App\Exports;

use App\Repository\TimesheetRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TimesheetExport implements FromView, WithColumnWidths, WithStyles
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        return view('timesheets.new-timesheet.export', [
            'taskSessions' => TimesheetRepository::getCsvTimesheetData($this->request),
            'totalTaskTimeTaken' => TimesheetRepository::totalTimeForTask($this->request),
        ]);
    }

    public function columnWidths(): array
    {
        $columnWidth['A'] = 36;
        $columnWidth['B'] = 16;
        $columnWidth['c'] = 20;
        $columnWidth['D'] = 56;

        return $columnWidth;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1' => ['font' => ['size' => 13, 'bold' => true]],
            'B1' => ['font' => ['size' => 13, 'bold' => true]],
            'C1' => ['font' => ['size' => 13, 'bold' => true]],
            'D1' => ['font' => ['size' => 13, 'bold' => true]],
            'E1' => ['font' => ['size' => 13, 'bold' => true]],
            'F1' => ['font' => ['size' => 13, 'bold' => true]],
            'G1' => ['font' => ['size' => 13, 'bold' => true]],
            'H1' => ['font' => ['size' => 13, 'bold' => true]],
            'I1' => ['font' => ['size' => 13, 'bold' => true]],
            'J1' => ['font' => ['size' => 13, 'bold' => true]],
            'K1' => ['font' => ['size' => 13, 'bold' => true]],
            'L1' => ['font' => ['size' => 13, 'bold' => true]],
            // Styling an entire column.
            'A' => ['font' => ['size' => 11, 'bold' => true]],
            'B' => ['font' => ['size' => 11]],
            'C' => ['font' => ['size' => 11]],
            'D' => ['font' => ['size' => 11]],
            'E' => ['font' => ['size' => 11]],
            'F' => ['font' => ['size' => 11]],
            'G' => ['font' => ['size' => 11]],
            'H' => ['font' => ['size' => 11]],
            'I' => ['font' => ['size' => 11]],
            'J' => ['font' => ['size' => 11]],
            'K' => ['font' => ['size' => 11]],
            'L' => ['font' => ['size' => 11]],
        ];
    }
}
