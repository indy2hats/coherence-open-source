<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAssignedUsers extends Model
{
    /** The attributes that are mass assignable */
    protected $fillable = [
        'project_id',
        'user_id'
    ];

    /** Get user/project managers details associated with a project */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /** Get project details associated with assigned users  */
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->active();
        });
    }
}
