<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    /** The attributes that are mass assignable */
    protected $fillable = [
        'reporting_to',
        'reportee',
    ];

    /** Get users associated with a team */
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function reporting_user()
    {
        return $this->belongsTo(User::class, 'reporting_to');
    }

    public function reportee_user()
    {
        return $this->belongsTo(User::class, 'reportee');
    }
}
