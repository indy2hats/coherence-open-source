<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxonomyList extends Model
{
    use HasFactory;

    protected $fillable = [
        'taxonomy_id',
        'user_id',
        'parent_id',
        'title',
        'slug',
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
