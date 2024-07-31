<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class ProjectDocuments extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'project_id',
        'name',
        'type',
        'path',
        'created_at'
    ];

    /** Get  project details associated with project document */
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    /** returns date format for end date */
    public function getCreatedAtFormatAttribute($date)
    {
        return date_format(new DateTime($date), 'd M, Y');
    }
}
