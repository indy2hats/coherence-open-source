<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    /** Attribute that are mass assignable*/
    protected $fillable = [
        'name',
    ];

    /** Department associated with user*/
    public function user()
    {
        return $this->hasMany('App\Models\User');
    }
}
