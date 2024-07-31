<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageOverhead extends Model
{
    /** The attributes that are mass assignable */
    protected $fillable = [
        'type',
        'amount',
        'description',
    ];
}
