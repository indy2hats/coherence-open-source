<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskRejection extends Model
{
    use HasFactory;

    /** The attributes that are mass assignable */
    protected $fillable = [
        'user_id',
        'task_id',
        'severity',
        'reason',
        'comments',
        'score',
        'rejected_by'
    ];

    /** Get users that belongs to task */
    public function users()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function task()
    {
        return $this->belongsTo('App\Models\Task', 'task_id', 'id');
    }

    public function rejectedBy()
    {
        return $this->belongsTo('App\Models\User', 'rejected_by', 'id');
    }

    public function exceed_reason()
    {
        return $this->hasOne('App\Models\TaskAssignedUsers', 'task_id , user_id', 'task_id , user_id');
    }

    public function qaIssue()
    {
        return $this->belongsTo('App\Models\QaIssue', 'reason', 'id');
    }
}
