<?php

namespace App\Repository;

use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\FixedOverhead;
use App\Models\MonthlyExpense;
use App\Models\Overhead;
use App\Models\Payroll;
use App\Models\PayrollUser;
use App\Models\Role;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseRepository
{
    use GeneralTrait{
        GeneralTrait::createOverHead as traitCreateOverHead;
    }

    public function getExpenses($year, $month)
    {
        return Expense::whereYear('date', $year)->whereMonth('date', $month)->get();
    }

    public function getLists($year, $month)
    {
        return DB::select('select * from overheads where YEAR(date) = ? and MONTH(date) = ?', [$year, $month]);
    }

    public function createOverhead()
    {
        $data = [
            'date' => $this->getDate(),
            'type' => request('type'),
            'amount' => request('amount'),
            'description' => request('description'),
        ];

        $type = $this->getOverHeadType();

        if ($type->count() == 0) {
            $this->createOverHeadType(['name' => request('type')]);
        }

        $this->traitCreateOverHead($data);
    }

    public function getDate()
    {
        return Carbon::createFromFormat('d/m/Y', request('date'))->format('Y-m-d');
    }

    public function getDateForDelete()
    {
        return Carbon::parse(request('date'))->startOfMonth();
    }

    public function getPayrollId()
    {
        return Payroll::where('status', 'complete')->latest()->first()->id;
    }

    public function getPayrollUsers()
    {
        $id = $this->getPayrollId();
        $users = PayrollUser::with(['user', 'user.role', 'user.department', 'user.designation'])->whereHas('user', function ($query) {
            $query->whereIn('role_id', Role::whereIn('name', ['administrator', 'project-manager', 'hr-manager', 'hr-associate'])->pluck('id')->toArray())
                ->where('status', 1);
        })->where('payroll_id', $id)->get()->sortBy('user.role_id');

        return $users;
    }

    public function getDataForPie()
    {
        return FixedOverhead::select('type', 'amount')->get();
    }

    public function getUsers()
    {
        return User::with('role', 'department', 'designation')->where('role_id', '!=', Role::where('name', 'client')->first()->id)->where('role_id', '!=', Role::where('name', 'employee')->first()->id)->where('role_id', '!=', Role::where('name', 'team-lead')->first()->id)->where('status', 1)->get();
    }

    public function getDataForLoadChartExpense()
    {
        $users = $this->getUsers();

        $data = [];

        foreach ($users as $user) {
            array_push($data, ['type' => $user->full_name, 'amount' => $user->monthly_salary]);
        }

        return $data;
    }

    public function getPie($year)
    {
        return DB::select('select type,sum(amount) as amount from overheads where YEAR(date) = ? GROUP by type', [$year]);
    }

    public function getChart($year)
    {
        return DB::select('select MONTH(date) as month,sum(amount) as amount from overheads where YEAR(date) = ? group by MONTH(date)', [$year]);
    }

    public function getTotal($year)
    {
        return DB::select('select sum(amount) as total from overheads where YEAR(date) = ?', [$year]);
    }

    public function getListsForListsTable()
    {
        $date = explode('-', request()->date);
        $year = $date[0];
        $month = $date[1];
        $lists = DB::select('select * from overheads where YEAR(date) = ? and MONTH(date) = ?', [$year, $month]);

        return $lists;
    }

    public function getExpenseType()
    {
        return ExpenseType::where('name', request('type'))->get();
    }

    public function getOverHeadLists($year, $month)
    {
        return Overhead::whereYear('date', $year)->whereMonth('date', $month)->get();
    }

    public function loadPieExpense()
    {
        $year = request('date') == null ? date('Y') : request('date');
        $pie = DB::select('select type,sum(amount) as amount from expenses where YEAR(date) = ? GROUP by type', [$year]);

        return $pie;
    }

    public function addToMonth()
    {
        $count = 0;
        $types = $this->getAllFixedOverHeads();
        foreach ($types as $type) {
            $check = Overhead::where('type', $type->type)->whereMonth('date', '=', date('m'))->get();
            if (count($check) == 0) {
                $this->createOverhead([
                    'date' => date('Y-m-d'),
                    'type' => $type->type,
                    'amount' => $type->amount,
                    'description' => $type->description,
                ]);
                $count++;
            }
        }

        return $count;
    }

    public static function getOverHeads($month, $year)
    {
        return Overhead::whereMonth('date', $month)
                    ->whereYear('date', $year)->sum('amount');
    }

    public static function getPayroll($month, $year)
    {
        return PayrollUser::join('payrolls', 'payrolls.id', '=', 'payroll_users.payroll_id')
            ->whereMonth('payroll_date', $month)
            ->whereYear('payroll_date', $year)
            ->select(DB::raw('(SUM(payroll_users.monthly_ctc) + SUM(payroll_users.incentives))  as salary'))->first();
    }

    public static function getExpense($month, $year)
    {
        return Expense::whereMonth('date', $month)
                ->whereYear('date', $year)->sum('amount');
    }

    public static function updateOrCreateMonthlyExpense($date, $totalExpense)
    {
        MonthlyExpense::updateOrCreate(
            ['month' => $date->startOfMonth()->format('Y-m-d')],
            ['expense' => $totalExpense]
        );
    }

    public static function checkOverHead($type, $date)
    {
        return Overhead::where('type', $type->type)->whereMonth('date', $date->month)->whereYear('date', $date->year)->get();
    }
}
