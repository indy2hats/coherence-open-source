<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeHikeHistory extends Model
{
    use HasFactory;
    protected $table = 'employee_hike_history';

    /** Attribute that are mass assignable*/
    protected $fillable = [
        'user_id',
        'hike',
        'previous_salary',
        'updated_salary',
        'date',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
