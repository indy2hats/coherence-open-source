<?php

namespace App\Models;

use App\Traits\IndianCurrency;
use Illuminate\Database\Eloquent\Model;

class PayrollUser extends Model
{
    use IndianCurrency;

    protected $fillable = [
        'payroll_id',
        'user_id',
        'gross_salary',
        'incentives',
        'total_earnings',
        'total_deductions',
        'net_salary',
        'monthly_ctc',
        'status',
        'no_of_leaves',
        'loss_of_pay'
    ];

    public function getEmployeeTotalEarningsAttribute()
    {
        return (float) $this->total_earnings;
    }

    public function getEmployeeTotalDeductionsAttribute()
    {
        return  (float) $this->total_deductions;
    }

    public function getEmployeeCtcAttribute()
    {
        return (float) $this->monthly_ctc;
    }

    public function getGrossAmountAttribute()
    {
        return  (float) $this->gross_salary;
    }

    public function getEmployeeNetSalaryAttribute()
    {
        return (float) $this->net_salary;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    public function getNetSalaryInWordsAttribute()
    {
        return  $this->get_words($this->net_salary);
    }

    public function getUserLeavesAttribute()
    {
        return (float) $this->no_of_leaves ?? 0;
    }

    public function getUserLossOfPayAttribute()
    {
        return (float) $this->loss_of_pay ?? 0;
    }

    public function getEmployeeExpenseAttribute()
    {
        $epfEmployeer = ($this->monthly_ctc ?? 0) - ($this->gross_salary ?? 0);

        return (float) ($this->total_earnings ?? 0) + $epfEmployeer;
    }
}
