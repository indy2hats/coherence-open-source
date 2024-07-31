<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $fillable = [
        'title',
        'category_id',
        'status',
        'tools'
    ];

    /** Get  category details associated with task checklist */
    public function category()
    {
        return $this->belongsTo('App\Models\ChecklistCategory', 'category_id', 'id');
    }
}
