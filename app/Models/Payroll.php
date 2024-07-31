<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_date',
        'total_amount',
        'incentives',
        'status'
    ];

    public function getMonthAttribute()
    {
        return date_format(new DateTime($this->payroll_date), 'M Y');
    }

    public function getFullMonthAttribute()
    {
        return date_format(new DateTime($this->payroll_date), 'F Y');
    }

    public function getPercentStatusAttribute()
    {
        $status = config('payroll.payrolls.status');

        return $this->status == $status[0] ? 50 : 100;
    }

    public function getFilterMonthAttribute()
    {
        return date_format(new DateTime($this->payroll_date), 'M-Y');
    }
}
