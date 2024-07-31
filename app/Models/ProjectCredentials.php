<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ProjectCredentials extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'project_id',
        'type',
        'value',
        'username',
        'password',
        'path',
        'created_at'
    ];

    /** Get  project details associated with project document */
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    /** returns date format for end date */
    public function getCreatedAtFormatAttribute()
    {
        return date_format(new DateTime($this->created_at), 'd M, Y');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'credential_assigned_users', 'credential_id', 'user_id');
    }

    public function getDecryptPasswordAttribute()
    {
        return ($this->password != null) ? Crypt::decryptString($this->password) : '';
    }
}
