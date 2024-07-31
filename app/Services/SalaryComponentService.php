<?php

namespace App\Services;

use App\Repository\SalaryComponentRepository;
use App\Traits\GeneralTrait;
use Exception;
use Illuminate\Http\Request;

class SalaryComponentService
{
    use GeneralTrait;

    protected $salaryComponentRepository;

    public function __construct(SalaryComponentRepository $salaryComponentRepository)
    {
        $this->salaryComponentRepository = $salaryComponentRepository;
    }

    public function store(Request $request)
    {
        $typeRule = 'required|in:'.implode(',', config('payroll.salary_component.type'));
        $request->validate([
            'title' => 'required|min:3|max:100',
            'type' => $typeRule,
        ]);
        try {
            $data = [
                'title' => trim(request('title')),
                'type' => trim(request('type')),
                'status' => request('status') == 'on' ? 1 : 0,
            ];

            $this->createSalaryComponent($data);

            $type = config('payroll.salary_component.type');
            $components = $this->getFullSalaryComponents();
            $defaultComponents = $this->getDefaultComponents();
            $content = view('payroll.salary-component.list', compact('type', 'components', 'defaultComponents'))->render();
            $res = [
                'status' => 200,
                'message' => config('payroll.salary_component.success_message.create'),
                'data' => $content
            ];

            return response()->json($res);
        } catch (Exception $e) {
            return false;
            $res = [
                'status' => 400,
                'message' => config('payroll.salary_component.error.message'),
            ];

            return response()->json($res);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $typeRule = 'required|in:'.implode(',', config('payroll.salary_component.type'));
            $request->validate([
                'edit_title' => 'required|min:3|max:100',
                'edit_type' => $typeRule,
            ]);

            $data = [
                'title' => trim(request('edit_title')),
                'type' => trim(request('edit_type')),
                'status' => request('edit_status') == 'on' ? 1 : 0,
            ];

            $this->updateSalaryComponent($id, $data);

            $type = config('payroll.salary_component.type');
            $components = $this->getFullSalaryComponents();
            $defaultComponents = $this->getDefaultComponents();
            $content = view('payroll.salary-component.list', compact('type', 'components', 'defaultComponents'))->render();
            $res = [
                'status' => 200,
                'message' => config('payroll.salary_component.success_message.update'),
                'data' => $content
            ];

            return response()->json($res);
        } catch (Exception $e) {
            $res = [
                'status' => 400,
                'message' => config('payroll.salary_component.error.message'),
            ];

            return response()->json($res);
        }
    }

    public function destroy($id)
    {
        try {
            $this->deleteSalaryComponent($id);

            $type = config('payroll.salary_component.type');
            $components = $this->getFullSalaryComponents();
            $defaultComponents = $this->getDefaultComponents();
            $content = view('payroll.salary-component.list', compact('type', 'components', 'defaultComponents'))->render();

            $res = [
                'status' => 200,
                'message' => config('payroll.salary_component.success_message.delete'),
                'data' => $content
            ];

            return response()->json($res);
        } catch (Exception $e) {
            $res = [
                'status' => 400,
                'message' => config('payroll.salary_component.error.message'),
            ];

            return response()->json($res);
        }
    }

    public static function getDefaultComponents()
    {
        $defaultComponents = config('payroll.payrolls.default_csv_headings');
        array_shift($defaultComponents);
        array_shift($defaultComponents);

        return $defaultComponents;
    }

    public function getSalaryComponents()
    {
        return $this->salaryComponentRepository->getSalaryComponents();
    }

    public function getFullSalaryComponents()
    {
        return $this->salaryComponentRepository->getFullSalaryComponents();
    }
}
