<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compensatory extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'session',
        'user_id',
        'reason',
        'status',
        'reason_for_rejection',
        'approved_by',
    ];

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function user_approved()
    {
        return $this->hasOne('App\Models\User', 'id', 'approved_by');
    }

    public function getdateFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->date), 'd/m/Y'));
    }
}
