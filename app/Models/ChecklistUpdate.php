<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistUpdate extends Model
{
    protected $fillable = [
        'user_id',
        'parent_id',
        'checklists',
    ];
}
