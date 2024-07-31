<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CredentialAssignedUsers extends Model
{
    protected $fillable = [
        'credential_id',
        'user_id',
    ];

    public function credential()
    {
        return $this->belongsTo('App\Models\ProjectCredentials');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
