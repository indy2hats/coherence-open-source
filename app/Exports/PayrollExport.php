<?php

namespace App\Exports;

use App\Repository\PayrollRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayrollExport implements FromCollection, WithHeadings, WithColumnWidths
{
    protected $data;
    private $labels;
    private $payrollDate;

    public function __construct($date)
    {
        $this->payrollDate = $date;
    }

    public function collection()
    {
        $employeeList = PayrollRepository::getCsvEmployeeData($this->payrollDate);
        $csvData = [];
        $i = 0;
        unset($this->label[21]);
        unset($this->label[1]);
        unset($this->label[2]);
        foreach ($employeeList as $employee) {
            foreach ($this->labels as $column) {
                $csvData[$i][lcfirst($column)] = 0;
            }
            $leaves = 0;
            if ($employee->leaves->isNotEmpty()) {
                $leaves = $employee->leaves->where('session', 'Full Day')->count();
                $leaves += $employee->leaves->where('session', '<>', 'Full Day')->count() / 2;
            }
            $csvData[$i]['employee ID'] = $employee->employee_id;
            $csvData[$i]['employee Name'] = $employee->full_name;
            $csvData[$i]['no of Leaves'] = (string) $leaves;
            $i++;
        }

        return collect($csvData);
    }

    public function headings(): array
    {
        $headings = PayrollRepository::getPayrollComponents();
        $defaultHeadings = config('payroll.payrolls.default_csv_headings');
        foreach ($defaultHeadings as $key => $heading) {
            array_splice($headings, $key, 0, $heading);
        }
        $this->labels = $headings;

        return $headings;
    }

    public function columnWidths(): array
    {
        $columnWidth['A'] = 13;
        $columnWidth['B'] = 30;
        for ($i = 2; $i < 20; $i++) {
            $columnWidth[chr($i + 65)] = 16;
        }

        return $columnWidth;
    }
}
