<?php

namespace App\Http\Controllers\Settings\OverHeadsAndExpenses;

use App\Http\Controllers\Controller;
use App\Services\MonthlyExpenseService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class FixedOverHeadsController extends Controller
{
    use GeneralTrait;

    protected $expenseService;

    public function __construct(MonthlyExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index()
    {
        $types = $this->getFixedOverHeads();
        $users = $this->expenseService->getPayrollUsers();

        return view('settings.manage-overhead.index', compact('types', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'amount' => 'required',
            'description' => 'required',
        ]);

        $data = [
            'type' => request('type'),
            'amount' => request('amount'),
            'description' => request('description'),
        ];

        $this->createFixedOverHead($data);

        $types = $this->getFixedOverHeads();
        $content = view('settings.manage-overhead.table', compact('types'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Overhead Created successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function edit($id)
    {
        $overhead = $this->getFixedOverHeadById($id);

        return view('settings.manage-overhead.edit', compact('overhead'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
            'amount' => 'required',
            'description' => 'required',
        ]);

        $data = [
            'type' => request('type'),
            'amount' => request('amount'),
            'description' => request('description'),
        ];

        $this->updateFixedOverHead($id, $data);
        $types = $this->getFixedOverHeads();
        $content = view('settings.manage-overhead.table', compact('types'))->render();

        $res = [
            'status' => 'Saved',
            'message' => 'Overhead Updated successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function destroy($id)
    {
        $this->deleteFixedOverHead($id);

        $types = $this->getFixedOverHeads();
        $content = view('settings.manage-overhead.table', compact('types'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Overhead Deleted successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function loadPie()
    {
        $data = $this->expenseService->getDataForPie();

        return response()->json(['data' => $data]);
    }

    public function loadChartExpense()
    {
        $data = $this->expenseService->getDataForLoadChartExpense();

        return response()->json(['data' => $data]);
    }

    public function addToMonth()
    {
        $count = $this->expenseService->addToMonth();

        if ($count == 0) {
            return response()->json(['success' => false, 'message' => 'Nothing new to add to this Month']);
        } else {
            return response()->json(['success' => true, 'message' => $count.' Expenses added to this month']);
        }
    }
}
