<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistCategory extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'status'
    ];

    /**
     * The checklists that belong to the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checklists()
    {
        return $this->hasMany(Checklist::class, 'category_id', 'id');
    }

    public function getChecklistsCountAttribute()
    {
        return $this->checklists->count();
    }
}
