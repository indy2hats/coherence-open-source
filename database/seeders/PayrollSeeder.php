<?php

namespace Database\Seeders;

use App\Models\Payroll;
use App\Models\PayrollUser;
use App\Models\PayrollUserSalaryComponent;
use App\Models\SalaryComponent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Payroll::factory()->count(1)->create(['payroll_date' => Carbon::now()->subMonths(1)->format('Y-m-01')])->each(function ($payroll, $index) {
            $totalAmount = 0;
            $totalIncentive = 0;
            $userList = self::getCsvEmployeeData();
            foreach ($userList as $user) {
                $eachUserPayroll = self::createPayrollUser($user, $payroll);
                $totalAmount += $eachUserPayroll['total'];
                $totalIncentive += $eachUserPayroll['incentives'];
            }
            $payrollData = [
                'total_amount' => $totalAmount,
                'incentives' => $totalIncentive
            ];
            Payroll::find($payroll->id)->update($payrollData);
        });
    }

    public static function getCsvEmployeeData()
    {
        return User::active()
            ->select('id')
            ->whereNotIn('role_id', [8, 4])
            ->orderBy('joining_date', 'Asc')->get();
    }

    public static function createPayrollUser($user, $payroll)
    {
        $payrollUserId = PayrollUser::create([
            'payroll_id' => $payroll->id,
            'user_id' => $user->id,
            'gross_salary' => 0,
            'incentives' => 0,
            'total_earnings' => 0,
            'total_deductions' => 0,
            'net_salary' => 0,
            'monthly_ctc' => 0,
            'loss_of_pay' => 0,
            'no_of_leaves' => rand(0, 5),
        ])->id;
        $payrollData = self::payrollSalaryComponent($payrollUserId);
        $minMonthly = ($payrollData['total_earnings'] + $payrollData['total_deductions']) + 1000;
        $payrollData['incentives'] = rand(1000, 10000);
        $payrollData['total_earnings'] += $payrollData['incentives'];
        $payrollData['monthly_ctc'] = rand($minMonthly, $minMonthly + rand(1000, 20000));
        $epfEmployeer = SalaryComponent::where(['title' => 'EPF Employer', 'status' => 1])->pluck('id')->toArray();
        if (! empty($epfEmployeer)) {
            $epfEmployeer = PayrollUserSalaryComponent::where(['salary_component_id' => $epfEmployeer['id'], 'payroll_user_id' => $payrollUserId])->pluck('amount')->toArray();
        }
        $esi = PayrollUserSalaryComponent::where(['salary_component_id' => 5, 'payroll_user_id' => $payrollUserId])->pluck('amount')->toArray();
        $payrollData['gross_salary'] = $payrollData['monthly_ctc'] - ($epf[0] ?? 0 + $esi[0] ?? 0);
        $payrollData['net_salary'] = $payrollData['gross_salary'] - $payrollData['total_deductions'];

        PayrollUser::find($payrollUserId)->update($payrollData);

        return [
            'total' => $payrollData['monthly_ctc'],
            'incentives' => $payrollData['incentives']
        ];
    }

    public static function payrollSalaryComponent($payrollUserId)
    {
        $salaryComponent = SalaryComponent::where('type', 'deduction')->pluck('id')->toArray();
        $deductionAmount = 0;
        foreach ($salaryComponent as $componentId) {
            $amt = rand(100, 1500);
            $deductionAmount += $amt;
            PayrollUserSalaryComponent::create([
                'salary_component_id' => $componentId,
                'payroll_user_id' => $payrollUserId,
                'amount' => $amt
            ]);
        }
        $earningComponent = SalaryComponent::where('type', 'earning')->pluck('id')->toArray();
        $earningAmount = 0;
        foreach ($earningComponent as $componentId) {
            $amt = rand(5000, 60000);
            $earningAmount += $amt;
            PayrollUserSalaryComponent::create([
                'salary_component_id' => $componentId,
                'payroll_user_id' => $payrollUserId,
                'amount' => $amt
            ]);
        }

        return [
            'total_earnings' => $earningAmount,
            'total_deductions' => $deductionAmount,
        ];
    }
}
