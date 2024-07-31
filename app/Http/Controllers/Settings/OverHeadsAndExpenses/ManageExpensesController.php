<?php

namespace App\Http\Controllers\Settings\OverHeadsAndExpenses;

use App\Http\Controllers\Controller;
use App\Services\MonthlyExpenseService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ManageExpensesController extends Controller
{
    use GeneralTrait;

    protected $expenseService;

    public function __construct(MonthlyExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = explode('-', date('Y-m-d'));
        $year = $date[0];
        $month = $date[1];

        $lists = $this->expenseService->getLists($year, $month);
        $expenses = $this->expenseService->getExpenses($year, $month);

        return view('general.overheads.index', compact('lists', 'expenses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:d/m/Y',
            'type' => 'required',
            'amount' => 'required',
            'description' => 'required',
        ]);

        $this->expenseService->createOverhead();

        $date = $this->expenseService->getDateDMY();
        $year = $date->year;
        $month = $date->month;

        $this->expenseService->createOrUpdateMonthlyExpense($date);
        $lists = $this->expenseService->getLists($year, $month);
        $expenses = $this->expenseService->getExpenses($year, $month);

        $content = view('general.overheads.index', compact('lists', 'expenses'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Overhead created successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $overhead = $this->getOverHeadById($id);

        return view('general.overheads.edit', compact('overhead'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'description' => 'required',
        ]);

        $data = [
            'date' => request('date'),
            'type' => request('type'),
            'amount' => request('amount'),
            'description' => request('description'),
        ];

        $this->updateOverHead($id, $data);

        $date = $this->expenseService->getDateYmd();
        $year = $date->year;
        $month = $date->month;
        $this->expenseService->createOrUpdateMonthlyExpense($date);
        $lists = $this->expenseService->getLists($year, $month);
        $expenses = $this->expenseService->getExpenses($year, $month);

        $content = view('general.overheads.index', compact('lists', 'expenses'))->render();

        $res = [
            'status' => 'Saved',
            'message' => 'Overhead Updated successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->deleteOverHead($id);

        $date = $this->expenseService->getDateForDelete();
        $year = $date->year;
        $month = $date->month;

        $this->expenseService->createOrUpdateMonthlyExpense($date);

        $lists = $this->expenseService->getLists($year, $month);
        $expenses = $this->expenseService->getExpenses($year, $month);
        $content = view('general.overheads.index', compact('lists', 'expenses'))->render();
        $res = [
            'status' => 'Saved',
            'message' => 'Overhead Deleted successfully',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function loadChart()
    {
        $year = request('date') == null ? date('Y') : request('date');
        $total = $this->expenseService->getTotal($year);
        $chart = $this->expenseService->getChart($year);
        $pie = $this->expenseService->getPie($year);

        return response()->json(['data1' => $chart, 'data2' => $pie, 'data3' => $total]);
    }

    public function listTable()
    {
        $lists = $this->expenseService->getListsForListsTable();

        $content = view('general.overheads.list', compact('lists'))->render();
        $res = [
            'status' => 'Saved',
            'data' => $content,
        ];

        return response()->json($res);
    }

    public function getTypes()
    {
        $types = $this->getOverHeadTypes();

        return response()->json(['data' => $types]);
    }

    //App\Http\Controllers\Settings\OverHeadsAndExpenses\ExpenseController
}
