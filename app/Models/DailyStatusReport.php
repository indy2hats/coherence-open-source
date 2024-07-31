<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStatusReport extends Model
{
    use HasFactory;
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
