<?php

namespace App\Services;

use App\Models\Expense;
use App\Repository\ExpenseRepository;
use App\Traits\GeneralTrait;
use Illuminate\Support\Carbon;

class MonthlyExpenseService
{
    use GeneralTrait{
        GeneralTrait::createOverHead as traitCreateOverHead;
    }

    protected $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public static function createOrUpdateMonthlyExpense($date)
    {
        $month = $date->month;
        $year = $date->year;
        $overheads = ExpenseRepository::getOverHeads($month, $year);
        $payroll = ExpenseRepository::getPayroll($month, $year);
        $expense = ExpenseRepository::getExpense($month, $year);

        $totalExpense = $overheads + $expense;
        $totalExpense += isset($payroll->salary) ? ($payroll->salary ?? 0) : 0;

        ExpenseRepository::updateOrCreateMonthlyExpense($date, $totalExpense);
    }

    public static function addMonthlyFixedOverheads($date)
    {
        $types = self::getAllFixedOverHeads();
        foreach ($types as $type) {
            $check = ExpenseRepository::checkOverHead($type, $date);
            if (count($check) == 0) {
                self::traitCreateOverHead([
                    'date' => $date->format('Y-m-d'),
                    'type' => $type->type,
                    'amount' => $type->amount,
                    'description' => $type->description,
                ]);
            }
        }
    }

    public function getExpenses($year, $month)
    {
        return $this->expenseRepository->getExpenses($year, $month);
    }

    public function getLists($year, $month)
    {
        return $this->expenseRepository->getLists($year, $month);
    }

    public function createOverhead()
    {
        return $this->expenseRepository->createOverhead();
    }

    public function getDateDMY()
    {
        return Carbon::createFromFormat('d/m/Y', request('date'));
    }

    public function getDateYmd()
    {
        return Carbon::createFromFormat('Y-m-d', request('date'));
    }

    public function getDateForDelete()
    {
        return $this->expenseRepository->getDateForDelete();
    }

    public function getPayrollUsers()
    {
        return $this->expenseRepository->getPayrollUsers();
    }

    public function getDataForPie()
    {
        return $this->expenseRepository->getDataForPie();
    }

    public function getDataForLoadChartExpense()
    {
        return $this->expenseRepository->getDataForLoadChartExpense();
    }

    public function getPie($year)
    {
        return $this->expenseRepository->getPie($year);
    }

    public function getChart($year)
    {
        return $this->expenseRepository->getChart($year);
    }

    public function getTotal($year)
    {
        return $this->expenseRepository->getTotal($year);
    }

    public function getListsForListsTable()
    {
        return $this->expenseRepository->getListsForListsTable();
    }

    public function getOverHeadLists($year, $month)
    {
        return $this->expenseRepository->getOverHeadLists($year, $month);
    }

    public function storeExpenses()
    {
        $data = [
            'date' => $this->expenseRepository->getDate(),
            'type' => request('type'),
            'amount' => request('amount'),
        ];

        $type = $this->expenseRepository->getExpenseType();

        if ($type->count() == 0) {
            $this->createExpenseType(['name' => request('type')]);
        }

        $this->createExpense($data);

        $date = $this->getDateDMY();
        $year = $date->year;
        $month = $date->month;

        $this->createOrUpdateMonthlyExpense($date);

        return Expense::whereYear('date', $year)->whereMonth('date', $month)->get();
    }

    public function loadPieExpense()
    {
        return $this->expenseRepository->loadPieExpense();
    }

    public function addToMonth()
    {
        return $this->expenseRepository->addToMonth();
    }
}
