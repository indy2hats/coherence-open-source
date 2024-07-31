<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Facades\App\Services\LeaveService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'from_date',
        'to_date',
        'type',
        'session',
        'lop',
        'reason',
        'status',
        'email_code',
        'approved_by',
        'reason_for_rejection',
        'created_at',
    ];

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function user_approved()
    {
        return $this->hasOne('App\Models\User', 'id', 'approved_by');
    }

    /** Get the date in day-month-Year. */
    public function getFromDateFormatAttribute()
    {
        return Carbon::parse($this->from_date)->format('d/m/Y');
    }

    public function getToDateFormatAttribute()
    {
        return Carbon::parse($this->to_date)->format('d/m/Y');
    }

    public function getCreatedAtFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->created_at), 'd/m/Y'));
    }

    /**Get total leave days count */
    public function getTotalLeaveDaysAttribute()
    {
        return LeaveService::getLeaveDaysCount($this->from_date, $this->to_date, $this->session);
    }

    public function scopeLeaveToday($query)
    {
        return $query->where('from_date', '<=', date('Y-m-d'))->where('to_date', '>=', date('Y-m-d'))->whereStatus('Approved');
    }

    public function scopeMonthLeavesCount($query, $date)
    {
        $firstDay = \Carbon\Carbon::parse($date)->startOfMonth()->toDateString();
        $lastDay = \Carbon\Carbon::parse($date)->endOfMonth()->toDateString();

        return $query->whereDate('from_date', '<=', $lastDay)->whereDate('to_date', '>=', $firstDay)->whereStatus('Approved')->get()->count() ?? 0;
    }
}
