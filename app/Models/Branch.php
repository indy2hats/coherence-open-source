<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    /** The attributes that are mass assignable */
    protected $fillable = [
        'task_id',
        'name',
        'url',
    ];

    /** Get tasks associated with a branch */
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}
