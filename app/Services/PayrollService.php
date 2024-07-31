<?php

namespace App\Services;

use App\Imports\PayrollImport;
use App\Models\Payroll;
use App\Repository\PayrollRepository;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class PayrollService
{
    use GeneralTrait;

    protected $payrollRepository;
    protected $monthlyExpenseService;

    public function __construct(PayrollRepository $payrollRepository, MonthlyExpenseService $monthlyExpenseService)
    {
        $this->payrollRepository = $payrollRepository;
        $this->monthlyExpenseService = $monthlyExpenseService;
    }

    private static function uploadPayrollFile($request)
    {
        try {
            $extension = $request->file->getClientOriginalExtension();
            $name = str_replace(' ', '-', $request['month_year']);
            $fileName = $name.'.'.$extension;
            $folderPath = public_path('uploads');
            if (! is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            $request->file->move($folderPath, $fileName);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private static function getPredefinedComponents()
    {
        try {
            $salaryHeadings = PayrollRepository::getPayrollComponents();
            $defaultHeadings = config('payroll.payrolls.default_csv_headings');
            foreach ($defaultHeadings as $key => $heading) {
                array_splice($salaryHeadings, $key, 0, $heading);
            }
            $payrollHeadings = array_map(function ($value) {
                return strtolower(str_replace(' ', '_', $value));
            }, $salaryHeadings);

            return $payrollHeadings;
        } catch (Exception $e) {
            return null;
        }
    }

    private static function getExcelComponents($file)
    {
        $excelheadings = (new HeadingRowImport())->toArray($file);
        $excelheadings = array_filter($excelheadings[0][0]);

        return $excelheadings;
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

    private static function successJsonResponse($year = null, $message = null)
    {
        if (empty($year)) {
            $year = date('Y');
        }
        $filterYear = (int) $year;
        $startDate = date($year.'-04-01');
        $endDate = date('Y-m-d', strtotime($year.'-03-31'.' +1 years'));
        $payrollRepo = new PayrollRepository(new Payroll);
        $payrolls = $payrollRepo->getPayRolls($startDate, $endDate);
        $content = view('payroll.monthly-payroll.list', compact('payrolls', 'filterYear'))->render();
        $res = [
            'status' => 200,
            'message' => $message ?? config('payroll.payrolls.success_message.import'),
            'data' => $content
        ];

        return response()->json($res);
    }

    public static function validateFileComponents($file)
    {
        try {
            $predefinedComponents = self::getPredefinedComponents();
            $excelComponents = self::getExcelComponents($file);
            $diff = array_diff($predefinedComponents, $excelComponents);
            if (! empty($diff)) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function importPayroll(Request $request)
    {
        try {
            $monthYear = date('Y-m-d', strtotime(request('month_year')));
            $checkPayrollExist = PayrollRepository::checkPayrollExist($monthYear);
            if ($checkPayrollExist == 1) {
                return self::errorJsonResponse(config('payroll.payrolls.error.processed_update_error'));
            }

            $file = $request['file'];
            $response = self::validateFileComponents($file);
            if (! $response) {
                return self::errorJsonResponse(config('payroll.payrolls.error.csv_components_mismatch'));
            }

            $payrollId = PayrollRepository::storePayroll($monthYear);
            Excel::import(new PayrollImport($payrollId), $request['file']);
            PayrollRepository::updateMonthlyPayrollData($payrollId);
            MonthlyExpenseService::createOrUpdateMonthlyExpense(Carbon::parse(request('month_year')));

            if ($request['file']) {
                $uploadFile = self::uploadPayrollFile($request);
                if ($uploadFile) {
                    return self::successJsonResponse($request['filter']);
                }
            }

            return self::errorJsonResponse();
        } catch (Exception $e) {
            return self::errorJsonResponse();
        }
    }

    public static function updatePayrollStatus($data, $year, $id)
    {
        try {
            $data = self::updatePayroll($id, $data);
            $successMessage = config('payroll.payrolls.success_message.status_update');

            return self::successJsonResponse($year, $successMessage);
        } catch (Exception $e) {
            return self::errorJsonResponse();
        }
    }

    public function getStartDate($year)
    {
        return date($year.'-04-01');
    }

    public function getEndDate($year)
    {
        return date($year.'-12-30');
    }

    public function getEndDateForFilter($year)
    {
        return date('Y-m-d', strtotime($year.'-03-31'.' +1 years'));
    }

    public function getPayRolls($startDate, $endDate)
    {
        return $this->payrollRepository->getPayRolls($startDate, $endDate);
    }

    public function getCurrentPayroll($id)
    {
        return $this->payrollRepository->getCurrentPayroll($id);
    }

    public function getPayrollById($id)
    {
        return $this->payrollRepository->getPayrollById($id);
    }
}
