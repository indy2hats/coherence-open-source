<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Redirect;
use PDF;

class EmployeePayslipService
{
    private static function notExistPayslipJsonResponse($userId, $date)
    {
        return [
            'companyDetails' => null,
            'employeePayroll' => null,
            'earingComponents' => null,
            'deductionComponents' => null,
            'monthYear' => self::monthYear($date),
            'id' => $userId
        ];
    }

    public static function monthYear($date)
    {
        return Carbon::parse($date)->format('M-Y');
    }

    public static function getPayslipComponents($userId, $date)
    {
        try {
            if (! preg_match('/^\w{3}\-\d{4}$/', $date)) {
                $date = self::getLastMonth();
            }
            $date = self::monthYear($date);
            $data = EmployeePayrollService::getPayslipComponents($userId, $date);
            if (is_null($data['employeePayroll'])) {
                return self::notExistPayslipJsonResponse($userId, $date);
            }
            $employeePayroll = $data['employeePayroll'];
            $status = config('payroll.payrolls.status');
            if ($employeePayroll->payroll->status == $status[0]) {
                return self::notExistPayslipJsonResponse($userId, $date);
            }

            return $data;
        } catch (Exception $e) {
            return self::notExistPayslipJsonResponse($userId, $date);
        }
    }

    public static function getLatestPayslipComponents($userId, $date)
    {
        try {
            $date = self::monthYear($date);
            $data = EmployeePayrollService::getPayslipComponents($userId, '');
            if (is_null($data['employeePayroll'])) {
                return self::notExistPayslipJsonResponse($userId, $date);
            }
            $employeePayroll = $data['employeePayroll'];
            $status = config('payroll.payrolls.status');
            if ($employeePayroll->payroll->status == $status[0]) {
                return self::notExistPayslipJsonResponse($userId, $date);
            }

            return $data;
        } catch (Exception $e) {
            return self::notExistPayslipJsonResponse($userId, $date);
        }
    }

    public static function generatePayslip($userId, $monthYear)
    {
        try {
            $data = EmployeePayrollService::getPayslipComponents($userId, $monthYear);
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

    public static function getLastMonth()
    {
        return new Carbon('first day of last month');
    }
}
