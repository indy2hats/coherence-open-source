<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',
        'amount'
    ];

    /** returns date format for date */
    public function getDateAttribute($date)
    {
        return date_format(new DateTime($date), 'Y-m-d');
    }
}
