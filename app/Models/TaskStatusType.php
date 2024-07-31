<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatusType extends Model
{
    /** The attributes that are mass assignable */
    protected $fillable = [
        'title',
        'order'
    ];
}
