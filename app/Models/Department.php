<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    /** Attribute that are mass assignable*/
    protected $fillable = [
        'name',
    ];

    /** Designation associated with user*/
    public function user()
    {
        return $this->hasMany('App\Models\User');
    }
}
