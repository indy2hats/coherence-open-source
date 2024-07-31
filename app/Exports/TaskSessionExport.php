<?php

namespace App\Exports;

use App\Repository\TaskSessionRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TaskSessionExport implements FromView, WithColumnWidths, WithStyles
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        return view('tasks.export', [
            'taskSessions' => TaskSessionRepository::getCsvTaskSessionData($this->request)
        ]);
    }

    public function columnWidths(): array
    {
        $columnWidth['A'] = 36;
        $columnWidth['B'] = 16;
        $columnWidth['c'] = 20;
        $columnWidth['D'] = 20;
        $columnWidth['E'] = 56;

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
            'A' => ['font' => ['size' => 11, 'bold' => true]],
            'B' => ['font' => ['size' => 11]],
            'C' => ['font' => ['size' => 11]],
            'D' => ['font' => ['size' => 11]],
            'E' => ['font' => ['size' => 11]],
        ];
    }
}
