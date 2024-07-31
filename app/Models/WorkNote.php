<?php

namespace App\Models;

use Auth;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkNote extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content'];

    /**return date format for created at */
    public function getUpdatedAtFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->updated_at), 'd/m/Y H:i:s'));
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::user()) {
                $model->user_id = Auth::user()->id;
                $model->content = '';
            }
        });
    }
}
