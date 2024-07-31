<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'holiday_date',
        'holiday_name',
    ];

    /** Get the date in day-month-Year. */
    public function getCreatedAtFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->created_at), 'd M Y'));
    }

    /** Get the date in day-month-Year. */
    public function getHolidayDateFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->holiday_date), 'd M Y'));
    }

    public function getEditDateAttribute()
    {
        return ucfirst(date_format(new DateTime($this->holiday_date), 'd/m/Y'));
    }
}
