<?php

namespace App\Repository;

use App\Models\Payroll;
use App\Models\PayrollUser;
use App\Models\PayrollUserSalaryComponent;
use App\Models\SalaryComponent;
use App\Models\Settings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollRepository
{
    protected $model;

    public function __construct(Payroll $payroll)
    {
        $this->model = $payroll;
    }

    public static function getPayrollComponents()
    {
        $headings = SalaryComponent::where('status', 1)->pluck('title')->toArray();

        return array_values($headings);
    }

    public static function getCsvEmployeeData($date)
    {
        $date = Carbon::parse($date);

        return User::active()->with('leaves', function ($query) use ($date) {
            $query->monthLeavesCount($date);
        })->whereDate('joining_date', '<=', $date->endOfMonth())
            ->where(function ($query) use ($date) {
                $query->whereDate('leaving_date', '=', date('0000-00-00'));
                $query->orWhereDate('leaving_date', '>=', $date->startOfMonth());
                $query->orWhereNull('leaving_date');
            })
            ->whereNotIn('role_id', [8, 4])
            ->orderBy('joining_date', 'Asc')->select('first_name', 'last_name', 'employee_id', 'id')->get();
    }

    public static function updateMonthlyPayrollData($payrollId)
    {
        $totalAmount = self::getTotalAmount($payrollId);
        $totalIncentives = self::getTotalIncentives($payrollId);
        Payroll::where('id', $payrollId)->update([
            'incentives' => $totalIncentives,
            'total_amount' => $totalAmount,
        ]);
    }

    public static function getTotalAmount($payrollId)
    {
        return PayrollUser::where('payroll_id', $payrollId)->sum('total_earnings');
    }

    public static function getTotalIncentives($payrollId)
    {
        return PayrollUser::where('payroll_id', $payrollId)->sum('incentives');
    }

    public static function storePayroll($monthYear)
    {
        $payroll = Payroll::updateOrCreate(['payroll_date' => $monthYear]);

        return $payroll->id;
    }

    public static function getEarningComponents()
    {
        return  SalaryComponent::where('type', 'earning')->select('title')->get();
    }

    public static function getDeductionComponents()
    {
        return  SalaryComponent::where('type', 'deduction')->pluck('title')->toArray();
    }

    public static function getSalaryComponents()
    {
        return  SalaryComponent::where('status', 1)->pluck('title', 'id')->toArray();
    }

    public static function getSalaryComponentSlug()
    {
        return  SalaryComponent::where('status', 1)->select('title')->get();
    }

    public static function getCurrentUserPayroll($id)
    {
        return PayrollUser::find($id);
    }

    public static function getCurrentUserSalaryComponents($id)
    {
        return DB::table('payroll_user_salary_components')
            ->join('salary_components', 'salary_components.id', '=', 'payroll_user_salary_components.salary_component_id')
            ->where('payroll_user_id', $id)
            ->orderBy('payroll_user_salary_components.salary_component_id', 'ASC')
            ->pluck('payroll_user_salary_components.amount', 'salary_components.title')
            ->toArray();
    }

    public static function getCurrentUserSalaryComponentsWithType($id)
    {
        return DB::table('payroll_user_salary_components')
            ->join('salary_components', 'salary_components.id', '=', 'payroll_user_salary_components.salary_component_id')
            ->where('payroll_user_id', $id)
            ->orderBy('payroll_user_salary_components.salary_component_id', 'ASC')
            ->select('payroll_user_salary_components.amount', 'salary_components.title', 'salary_components.type')
            ->get();
    }

    public static function updateEmployeeSalaryComponent($payrollUserId, $salaryComponentId, $amount)
    {
        PayrollUserSalaryComponent::where([
            'payroll_user_id' => $payrollUserId,
            'salary_component_id' => $salaryComponentId,
        ])->update(
            [
                'amount' => $amount ?? 0
            ]
        );
    }

    /**
     * Calculates the total deduction for an employee.
     *
     * @param  array  $row  The row containing the employee's data.
     * @return int The total deduction.
     */
    public static function getEmployeeTotalSalaryDeduction($row)
    {
        $deductionComponents = SalaryComponent::where('type', 'deduction')->pluck('title')->toArray();
        $totalDeduction = 0;
        foreach ($deductionComponents as $deduction) {
            $totalDeduction += $row[strtolower(str_replace(' ', '_', $deduction))];
        }

        return $totalDeduction;
    }

    public static function updateEmployeePayroll($payrollUserId, $request)
    {
        $totalDeduction = self::getEmployeeTotalSalaryDeduction($request);
        PayrollUser::where('id', $payrollUserId)
            ->update([
                'gross_salary' => $request['gross_salary'] ?? 0,
                'incentives' => $request['incentives'] ?? 0,
                'total_earnings' => $request['total_earnings'] ?? 0,
                'total_deductions' => $totalDeduction ?? 0,
                'net_salary' => $request['net_salary'] ?? 0,
                'monthly_ctc' => $request['monthly_ctc'] ?? 0,
                'loss_of_pay' => $request['loss_of_pay'] ?? 0,
                'no_of_leaves' => $request['no_of_leaves'] ?? 0,
            ]);
    }

    /**
     * Update the monthly payroll for a given employee.
     *
     * @param  int  $payrollUserId  The ID of the employee's payroll to be updated.
     * @return void
     */
    public static function updateMonthlyPayroll($payrollUserId)
    {
        $employeePayroll = PayrollUser::select('payroll_id')->where('id', $payrollUserId)->first();
        self::updateMonthlyPayrollData($employeePayroll->payroll_id);
    }

    /**
     * Checks if a payroll record exists for a given month and year.
     *
     * @param  string  $monthYear  The month and year in the format 'YYYY-MM'.
     * @return int The number of payroll records found, or 0 if the payroll is being processed.
     */
    public static function checkPayrollExist($monthYear)
    {
        $payrollCount = Payroll::where(['payroll_date' => $monthYear])->count();
        if ($payrollCount > 0) {
            $payroll = Payroll::where(['payroll_date' => $monthYear])->pluck('status')->toArray();
            if ($payroll[0] == 'processing') {
                $payroll = Payroll::where('payroll_date', $monthYear)->delete();

                return 0;
            }
        }

        return $payrollCount;
    }

    public static function getPayrollByDateAndUser($id, $payrollDate)
    {
        return PayrollUser::join('payrolls', 'payrolls.id', '=', 'payroll_users.payroll_id')
            ->where('user_id', $id)
            ->where('payroll_date', $payrollDate)
            ->first(['payroll_users.*', 'payrolls.payroll_date']);
    }

    public static function getLatestPayrollMonth($id, $payrollDate)
    {
        return Payroll::leftJoin('payroll_users', function ($join) {
            $join->on('payroll_users.payroll_id', '=', 'payrolls.id');
        })->where('payroll_users.user_id', $id)
            ->orderBy('payroll_date', 'DESC')
            ->select('payrolls.payroll_date')->first();
    }

    public static function getCompanyInfo()
    {
        return Settings::pluck('value', 'slug')->toArray();
    }

    public function getPayRolls($startDate, $endDate)
    {
        return $this->model::whereBetween('payroll_date', [$startDate, $endDate])->orderBy('payroll_date', 'Desc')->get();
    }

    public function getCurrentPayroll($id)
    {
        return $this->model::find($id);
    }

    public function getPayrollById($id)
    {
        return PayrollUser::where('payroll_id', $id)->orderBy('id', 'Asc')->get();
    }

    public function getEmployeePayroll($latestPayroll)
    {
        return PayrollUser::where('payroll_id', $latestPayroll->id)->orderBy('id', 'Asc')->get();
    }

    public function getLatestPayroll()
    {
        return $this->model::orderBy('payroll_date', 'DESC')->first();
    }
}
