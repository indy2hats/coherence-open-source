<?php

namespace App\Http\Controllers\Settings\OverHeadsAndExpenses;

use App\Http\Controllers\Controller;
use App\Services\MonthlyExpenseService;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    use GeneralTrait;

    protected $expenseService;

    public function __construct(MonthlyExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:d/m/Y',
            'type' => 'required',
            'amount' => 'required',
        ]);

        $expenses = $this->expenseService->storeExpenses();
        $content = view('general.overheads.expenses.list', compact('expenses'))->render();

        $res = [
            'status' => 'Saved',
            'message' => 'Expense created successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function edit($id)
    {
        $expense = $this->getExpenseById($id);

        return view('general.overheads.expenses.edit', compact('expense'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'type' => 'required',
            'amount' => 'required',
        ]);

        $data = [
            'date' => request('date'),
            'type' => request('type'),
            'amount' => request('amount'),
        ];

        $this->updateExpense($id, $data);

        $date = Carbon::parse(request()->date);
        $year = $date->year;
        $month = $date->month;

        $this->expenseService->createOrUpdateMonthlyExpense($date);
        $lists = $this->expenseService->getOverHeadLists($year, $month);
        $expenses = $this->expenseService->getExpenses($year, $month);

        $content = view('general.overheads.index', compact('lists', 'expenses'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Expense Updated successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function destroy($id)
    {
        $this->deleteExpense($id);

        $date = Carbon::parse(request()->date);
        $year = $date->year;
        $month = $date->month;

        $this->expenseService->createOrUpdateMonthlyExpense($date);

        $lists = $this->expenseService->getOverHeadLists($year, $month);
        $expenses = $this->expenseService->getExpenses($year, $month);

        $content = view('general.overheads.index', compact('lists', 'expenses'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Expense Deleted successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function getExpenseTypes()
    {
        $types = $this->getAllExpenseTypes();

        return response()->json(['data' => $types]);
    }

    public function listTable()
    {
        $date = Carbon::parse(request()->date);
        $year = $date->year;
        $month = $date->month;

        $expenses = $this->expenseService->getExpenses($year, $month);

        $content = view('general.overheads.expenses.list', compact('expenses'))->render();
        $res = [
            'status' => 'Saved',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function loadPieExpense()
    {
        $pie = $this->expenseService->loadPieExpense();

        return response()->json(['data' => $pie]);
    }
}
