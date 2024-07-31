<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskChecklist extends Model
{
    protected $fillable = [
        'task_id',
        'checklist_id',
        'developer_status',
        'reviewer_status'
    ];
}
