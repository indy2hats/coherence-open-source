<?php

namespace App\Http\Controllers\Payroll;

use App\Exports\PayrollExport;
use App\Http\Controllers\Controller;
use App\Services\EmployeePayrollService;
use App\Services\PayrollService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ManagePayrollController extends Controller
{
    use GeneralTrait;

    private $payrollService;
    private $employeePayrollService;

    public function __construct(PayrollService $payrollService, EmployeePayrollService $employeePayrollService)
    {
        $this->payrollService = $payrollService;
        $this->employeePayrollService = $employeePayrollService;
    }

    public function index()
    {
        $year = $this->getYear();
        $startDate = $this->payrollService->getStartDate($year);
        $endDate = $this->payrollService->getEndDate($year);
        $payrolls = $this->payrollService->getPayRolls($startDate, $endDate);
        $filterYear = (int) $year;

        return view('payroll.monthly-payroll.index', compact('payrolls', 'filterYear'));
    }

    public function indexEmployee()
    {
        $data = $this->employeePayrollService->getEmployeePayroll();

        return view('payroll.employee-payroll.index', $data);
    }

    public function show($id)
    {
        $currentPayroll = $this->payrollService->getCurrentPayroll($id);
        $employeePayroll = $this->payrollService->getPayrollById($id);

        return view('payroll.employee-payroll.index', compact('currentPayroll', 'employeePayroll'));
    }

    public function showEmployee($id, $monthYear = '')
    {
        $data = $this->employeePayrollService->getPayslipComponents($id, $monthYear);

        return view('payroll.employee-payroll.show', $data);
    }

    public function filter()
    {
        $year = request()->year;
        $startDate = $this->payrollService->getStartDate($year);
        $endDate = $this->payrollService->getEndDateForFilter($year);
        $payrolls = $this->payrollService->getPayRolls($startDate, $endDate);
        $filterYear = (int) $year;

        return view('payroll.monthly-payroll.index', compact('payrolls', 'filterYear'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'month_year' => 'required|date_format:M Y',
        ]);
        $payrollDate = request('month_year');

        $response = Excel::download(new PayrollExport($payrollDate), 'Payroll.xlsx');
        ob_end_clean();

        return $response;
    }

    public function store(Request $request)
    {
        $request->validate([
            'month_year' => 'required|date_format:M Y',
            'file' => 'required|mimes:xlsx'
        ]);

        return $this->payrollService->importPayroll($request);
    }

    public function editEmployee($id)
    {
        return $this->employeePayrollService->getCurrentEmployeePayroll($id);
    }

    public function updateEmployee(Request $request, $id)
    {
        $input = $request->except(['_token', '_method']);
        $rules = array_map(
            function ($val) {
                return 'required|numeric';
            },
            $input
        );
        $request->validate($rules);

        return $this->employeePayrollService->updateEmployeePayroll($request, $id);
    }

    public function exportEmployee($id, $monthYear)
    {
        return $this->employeePayrollService->generatePayslip($id, $monthYear);
    }

    public function update($id)
    {
        $data = [
            'status' => request('status') ?? 'processing'
        ];
        $year = request('year');

        return $this->payrollService->updatePayrollStatus($data, $year, $id);
    }
}
