<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santa extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function giftee()
    {
        return $this->hasOne('App\Modlels\Santa', 'id', 'giftee_id');
    }
}
