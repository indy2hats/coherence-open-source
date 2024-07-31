<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Services\SalaryComponentService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class SalaryComponentController extends Controller
{
    use GeneralTrait;

    private $salaryComponentService;

    public function __construct(SalaryComponentService $salaryComponentService)
    {
        $this->salaryComponentService = $salaryComponentService;
    }

    public function index()
    {
        $type = config('payroll.salary_component.type');
        $defaultComponents = $this->salaryComponentService->getDefaultComponents();
        $components = $this->salaryComponentService->getSalaryComponents();

        return view('payroll.salary-component.index', compact('type', 'components', 'defaultComponents'));
    }

    public function store(Request $request)
    {
        $response = $this->salaryComponentService->store($request);

        return $response;
    }

    public function edit($id)
    {
        $component = $this->getSalaryComponentById($id);
        $type = config('payroll.salary_component.type');

        return view('payroll.salary-component.edit', compact('type', 'component'));
    }

    public function destroy($id)
    {
        return $this->salaryComponentService->destroy($id);
    }

    public function update(Request $request, $id)
    {
        return $this->salaryComponentService->update($request, $id);
    }
}
