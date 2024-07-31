<?php

namespace App\Imports;

use App\Models\PayrollUser;
use App\Models\PayrollUserSalaryComponent;
use App\Repository\PayrollRepository;
use App\Repository\UserRepository;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PayrollImport implements WithHeadingRow, WithCalculatedFormulas, ToModel
{
    use Importable;

    private $payrollId;

    public function __construct($payrollId = '')
    {
        $this->payrollId = $payrollId;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        $deductionComponents = PayrollRepository::getDeductionComponents();
        $totalDeduction = 0;
        foreach ($deductionComponents as $deduction) {
            $deductionValue = $row[strtolower(str_replace(' ', '_', $deduction))];
            $totalDeduction += $this->filterValue($deductionValue ?? 0);
        }

        $userId = UserRepository::getUserId($row['employee_id']);
        $salaryComponents = PayrollRepository::getSalaryComponents();
        $payrollUser = PayrollUser::updateOrCreate([
            'payroll_id' => $this->payrollId,
            'user_id' => $userId,
        ], [
            'gross_salary' => $this->filterValue($row['gross_salary'] ?? 0),
            'incentives' => $this->filterValue($row['incentives'] ?? 0),
            'total_earnings' => $this->filterValue($row['total_earnings'] ?? 0),
            'total_deductions' => $this->filterValue($totalDeduction ?? 0),
            'net_salary' => $this->filterValue($row['net_salary'] ?? 0),
            'monthly_ctc' => $this->filterValue($row['monthly_ctc'] ?? 0),
            'loss_of_pay' => $this->filterValue($row['loss_of_pay'] ?? 0),
            'no_of_leaves' => $this->filterValue($row['no_of_leaves'] ?? 0),
            'status' => 'pending',
        ]);

        foreach ($salaryComponents as $componentId => $salaryComponent) {
            $salaryComponent = strtolower(str_replace(' ', '_', $salaryComponent));
            PayrollUserSalaryComponent::updateOrCreate(
                [
                    'payroll_user_id' => $payrollUser->id,
                    'salary_component_id' => $componentId,
                ],
                [
                    'amount' => $this->filterValue($row[$salaryComponent] ?? 0)
                ]
            );
        }

        return $payrollUser;
    }

    private function filterValue($value)
    {
        return  (float) preg_replace('~[\',]~', ' ', $value);
    }
}
