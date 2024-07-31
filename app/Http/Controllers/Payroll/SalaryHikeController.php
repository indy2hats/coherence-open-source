<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SalaryHikeService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class SalaryHikeController extends Controller
{
    use GeneralTrait;

    public $pagination;
    protected $salaryHikeService;

    public function __construct(SalaryHikeService $salaryHikeService)
    {
        $this->pagination = config('general.pagination');
        $this->salaryHikeService = $salaryHikeService;
    }

    public function index(Request $request)
    {
        $currentYear = $this->getYear();
        $employeeHikeHistory = $this->salaryHikeService->getEmployeeHikeHistory($currentYear, $this->pagination);
        $employees = $this->getEmployees();
        $salaryCurrency = $this->getSalaryCurrency();
        $employeesWithoutHike = $this->salaryHikeService->getEmployeesWithoutHike();

        return view('salary-hike.index', compact('employees', 'employeeHikeHistory', 'salaryCurrency', 'employeesWithoutHike'));
    }

    /**
     * Get asset search parameters.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchSalaryHike()
    {
        $employees = $this->getEmployees();
        $salaryCurrency = $this->getSalaryCurrency();
        $employeeHikeHistory = $this->salaryHikeService->getEmployeeHikeHistoryForSearch($this->pagination);
        $employeesWithoutHike = $this->salaryHikeService->getEmployeesWithoutHike();

        return view('salary-hike.index', compact('employees', 'employeeHikeHistory', 'salaryCurrency', 'employeesWithoutHike'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate(
            [
                'employee' => 'required',
                'previous_salary' => 'required',
                'hike' => 'required',
                'updated_salary' => 'required',
                'date' => 'required',
                'notes' => 'required'
            ]
        );

        $this->salaryHikeService->store();

        $res = [
            'status' => 'Saved',
            'message' => 'Salary Hike has been created successfully',
        ];

        return response()->json($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hikeHistory = $this->getEmployeeHikeHistoryById($id);
        $employee = User::find($hikeHistory->user_id);
        $employeeHikeHistory = $this->salaryHikeService->getEmployeeHikeHistoryForUserId($hikeHistory);
        $salaryCurrency = $this->getSalaryCurrency();

        return view('salary-hike.view', compact('hikeHistory', 'employeeHikeHistory', 'employee', 'salaryCurrency'));
    }

    public function employeeHikeHistory($employeeId)
    {
        $employee = User::find($employeeId);
        $hikeHistory = $this->salaryHikeService->getHikeHistoryForEmployeeId($employeeId);
        $salaryCurrency = $this->getSalaryCurrency();

        return view('salary-hike.employee', compact('hikeHistory', 'employee', 'salaryCurrency'));
    }
}
