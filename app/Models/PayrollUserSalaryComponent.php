<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollUserSalaryComponent extends Model
{
    protected $fillable = [
        'payroll_user_id',
        'salary_component_id',
        'amount'
    ];

    public function salary()
    {
        return $this->belongsTo(SalaryComponent::class, 'salary_component_id');
    }

    public function payrollUser()
    {
        return $this->belongsTo(PayrollUser::class, 'payroll_user_id');
    }
}
