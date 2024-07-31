<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userDeviceToken extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'token'
    ];
}
