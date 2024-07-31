<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    protected $fillable = [
        'name',
        'status'
    ];

    public function projects()
    {
        return $this->hasMany('App\Models\Project');
    }
}
