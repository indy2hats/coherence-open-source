<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'note',
        'checklists',
        'added_on'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
