<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskApprover extends Model
{
    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'status'
    ];

    /** Get task assigned users associated with task */
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }

    /** Get user/task  details associated with a task */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
