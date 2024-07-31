<?php

namespace App\Http\Controllers;

use App\Services\EmployeePayslipService;
use App\Traits\GeneralTrait;

class EmployeePayslipController extends Controller
{
    use GeneralTrait;

    private $employeePayslipService;

    public function __construct(EmployeePayslipService $employeePayslipService)
    {
        $this->employeePayslipService = $employeePayslipService;
    }

    public function index()
    {
        $lastMonth = $this->employeePayslipService->getLastMonth();
        $data = $this->employeePayslipService->getPayslipComponents($this->getCurrentUserId(), $lastMonth);

        return view('payroll.payslip.index', $data);
    }

    public function show($monthYear = '')
    {
        $data = $this->employeePayslipService->getPayslipComponents($this->getCurrentUserId(), $monthYear);

        return view('payroll.payslip.index', $data);
    }

    public function export($monthYear)
    {
        return $this->employeePayslipService->generatePayslip($this->getCurrentUserId(), $monthYear);
    }
}
