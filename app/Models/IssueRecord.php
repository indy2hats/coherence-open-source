<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueRecord extends Model
{
    use HasFactory;

    protected $guarded = [];

    /** returns date format for start date */
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    /** Get project asscoiated with issue */
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    /** Get project asscoiated with issue */
    public function addedBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'added_by');
    }
}
