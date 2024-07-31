<?php

namespace App\Services;

use App\Models\Payroll;
use App\Models\PayrollUser;
use App\Repository\PayrollRepository;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Redirect;
use PDF;

class EmployeePayrollService
{
    use GeneralTrait;
    protected $payrollRepository;

    public function __construct(PayrollRepository $payrollRepository)
    {
        $this->payrollRepository = $payrollRepository;
    }

    private static function errorJsonResponse($errorMessage = null)
    {
        if (empty($errorMessage)) {
            $errorMessage = config('payroll.payrolls.error.message');
        }
        $res = [
            'status' => 400,
            'message' => $errorMessage,
        ];

        return response()->json($res);
    }

    private static function successJsonResponse($userPayroll, $userPayrollSalaryComponent, $payrollComponents)
    {
        return  view('payroll.employee-payroll.edit', compact('userPayroll', 'payrollComponents', 'userPayrollSalaryComponent'))->render();
    }

    private static function getEmployeePayrollComponents($userPayroll, $userPayrollSalaryComponent)
    {
        try {
            $payrollComponents = PayrollRepository::getPayrollComponents();
            $defaultHeadings = config('payroll.payrolls.default_csv_headings');
            foreach ($defaultHeadings as $key => $component) {
                array_splice($payrollComponents, $key, 0, $component);
            }
            $defaultHeadings = array_values($defaultHeadings);
            $payrollComponent = [];
            foreach ($payrollComponents as $key => $component) {
                if ($key == 0) {
                    $payrollComponent[$component] = $userPayroll->user->employee_id;
                } elseif ($key == 1) {
                    $payrollComponent[$component] = $userPayroll->user->full_name;
                } elseif (in_array($component, $defaultHeadings)) {
                    $payrollComponent[$component] = $userPayroll[strtolower(str_replace(' ', '_', $component))] ?? 0;
                } else {
                    $payrollComponent[$component] = $userPayrollSalaryComponent[$component] ?? 0;
                }
            }

            return $payrollComponent ?? false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getCurrentEmployeePayroll($id)
    {
        try {
            $userPayroll = PayrollRepository::getCurrentUserPayroll($id);
            $userPayrollSalaryComponent = PayrollRepository::getCurrentUserSalaryComponents($id);
            $payrollComponents = self::getEmployeePayrollComponents($userPayroll, $userPayrollSalaryComponent);

            if (! $payrollComponents) {
                return self::errorJsonResponse();
            }

            return self::successJsonResponse($userPayroll, $userPayrollSalaryComponent, $payrollComponents);
        } catch (Exception $e) {
            return self::errorJsonResponse();
        }
    }

    private static function checkGrossSalary($request)
    {
        $calculatedGrossSalary = $request['monthly_ctc'] - ($request['epf'] + $request['esi']);

        return $request['gross_salary'] == $calculatedGrossSalary ? true : false;
    }

    private static function checkNetSalary($request)
    {
        $deductions = PayrollRepository::getEmployeeTotalSalaryDeduction($request);
        $calculatedNetSalary = $request['gross_salary'] - $deductions;

        return $request['net_salary'] == $calculatedNetSalary ? true : false;
    }

    private static function checkTotalEarnings($request, $payrollUserId)
    {
        $earningComponents = PayrollRepository::getEarningComponents();
        $totalEarnings = 0;
        foreach ($earningComponents as $component) {
            $totalEarnings += $request[$component->slug_component];
        }
        $calculatedTotalEarnings = $totalEarnings + $request['incentives'];

        return $request['total_earnings'] == $calculatedTotalEarnings ? true : false;
    }

    private static function verifyEmployeePayroll($request, $payrollUserId)
    {
        if (! self::checkGrossSalary($request)) {
            return false;
        }

        if (! self::checkTotalEarnings($request, $payrollUserId)) {
            return false;
        }

        if (! self::checkNetSalary($request, $payrollUserId)) {
            return false;
        }

        return true;
    }

    private static function updateSuccessJsonResponse($message, $id)
    {
        $employeePayroll = PayrollUser::select('payroll_id')->where('id', $id)->first();
        $currentPayroll = Payroll::find($employeePayroll->payroll_id);
        $employeePayroll = PayrollUser::where('payroll_id', $employeePayroll->payroll_id)->orderBy('id', 'Asc')->get();
        $content = view('payroll.employee-payroll.list', compact('currentPayroll', 'employeePayroll'))->render();
        $res = [
            'status' => 200,
            'message' => $message,
            'data' => $content,
        ];

        return response()->json($res);
    }

    public static function updateEmployeePayroll($request, $payrollUserId)
    {
        try {
            $verifyPayroll = self::verifyEmployeePayroll($request, $payrollUserId);
            if (! $verifyPayroll) {
                return self::errorJsonResponse(config('payroll.payroll_user.error.calculation_mistmatch'));
            }
            $salaryComponents = PayrollRepository::getSalaryComponents();
            foreach ($salaryComponents as $salaryComponentId => $salaryComponent) {
                $salaryComponent = strtolower(str_replace(' ', '_', $salaryComponent));

                PayrollRepository::updateEmployeeSalaryComponent($payrollUserId, $salaryComponentId, $request[$salaryComponent]);
            }
            PayrollRepository::updateEmployeePayroll($payrollUserId, $request);
            PayrollRepository::updateMonthlyPayroll($payrollUserId);

            $monthlyExpenseDate = PayrollUser::where('id', $payrollUserId)->first()->payroll->payroll_date;
            MonthlyExpenseService::createOrUpdateMonthlyExpense(Carbon::parse($monthlyExpenseDate));

            $message = config('payroll.payroll_user.update_success.message');

            return self::updateSuccessJsonResponse($message, $payrollUserId);
        } catch (Exception $e) {
            return self::errorJsonResponse();
        }
    }

    private static function roundOffComponents($valueArray)
    {
        return array_map(function ($value) {
            return floor($value);
        }, $valueArray);
    }

    public static function getPayslipComponents($id, $payrollDate)
    {
        try {
            if (empty($payrollDate)) {
                $payrollDate = PayrollRepository::getLatestPayrollMonth($id, $payrollDate);
                $payrollDate = $payrollDate->filter_month;
            }

            $payrollDate = date_format(new DateTime($payrollDate), 'Y-m-d');
            $employeePayroll = PayrollRepository::getPayrollByDateAndUser($id, $payrollDate);
            $salaryComponents = PayrollRepository::getCurrentUserSalaryComponentsWithType($employeePayroll->id);

            $earingComponents = $salaryComponents->where('type', 'earning')->pluck('amount', 'title')->toArray();
            $deductionComponents = $salaryComponents->where('type', 'deduction')->pluck('amount', 'title')->toArray();
            $companyInfo = PayrollRepository::getCompanyInfo();

            return [
                'companyDetails' => $companyInfo,
                'employeePayroll' => $employeePayroll,
                'earingComponents' => $earingComponents,
                'deductionComponents' => $deductionComponents,
                'monthYear' => date_format(new DateTime($employeePayroll->payroll->month), 'M-Y'),
                'id' => $id
            ];
        } catch (Exception $e) {
            return [
                'companyDetails' => null,
                'employeePayroll' => null,
                'earingComponents' => null,
                'deductionComponents' => null,
                'monthYear' => date_format(new DateTime($payrollDate), 'M-Y'),
                'id' => $id
            ];
        }
    }

    public static function generatePayslip($id, $monthYear)
    {
        try {
            $data = self::getPayslipComponents($id, $monthYear);
            $pdf = PDF::loadView('payroll.employee-payroll.pdf-template', $data);
            $employeePayroll = $data['employeePayroll'];
            $filename = str_replace(' ', '-', $employeePayroll->user->full_name.' '.$employeePayroll->payroll->month);
            $status = config('payroll.payrolls.status');
            if ($employeePayroll->payroll->status == $status[0]) {
                return Redirect::back()->withErrors(['export_error_message' => config('payroll.payroll_user.error.export_processing_error_message')]);
            }

            return $pdf->download($filename.'.pdf');
        } catch (Exception $e) {
            return Redirect::back()->withErrors(['export_error_message' => config('payroll.payroll_user.error.export_error_message')]);
        }
    }

    public function getEmployeePayroll()
    {
        try {
            $latestPayroll = $this->payrollRepository->getLatestPayroll();
            $currentPayroll = $this->findPayrollById($latestPayroll->id);
            $employeePayroll = $this->payrollRepository->getEmployeePayroll($latestPayroll);

            return [
                'currentPayroll' => $currentPayroll,
                'employeePayroll' => $employeePayroll
            ];
        } catch (Exception $e) {
            return null;
        }
    }
}
