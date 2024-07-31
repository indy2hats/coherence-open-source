<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overhead extends Model
{
    use HasFactory;

    /** The attributes that are mass assignable */
    protected $fillable = [
        'date',
        'type',
        'amount',
        'description',
    ];

    /** returns date format for date */
    public function getDateAttribute($date)
    {
        return date_format(new DateTime($date), 'Y-m-d');
    }

    /** Get the date in day-month-Year. */
    public function getInDateFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->date), 'd M Y'));
    }
}
