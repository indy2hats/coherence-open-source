<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWish extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'date',
        'image',
        'title',
        'type',
        'file_type',
        'user_id'
    ];

    public function getDateFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->date), 'M d, Y'));
    }
}
