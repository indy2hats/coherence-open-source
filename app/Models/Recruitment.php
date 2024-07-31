<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'location',
        'resume',
        'description',
        'category',
        'source',
        'career_start_date',
        'status',
        'applied_date'
    ];

    public function getCreatedAtAttribute($date)
    {
        return date_format(new DateTime($date), 'd/m/Y');
    }

    public function getAppliedDateAttribute($date)
    {
        return date_format(new DateTime($date), 'd/m/Y');
    }

    public function schedule()
    {
        return $this->hasOne('App\Models\Schedule');
    }

    public function getCareerStartDateFormatAttribute()
    {
        return is_null($this->career_start_date) ? null : ucfirst(date_format(new DateTime($this->career_start_date), 'd/m/Y'));
    }
}
