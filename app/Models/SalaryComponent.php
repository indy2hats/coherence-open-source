<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    protected $fillable = [
        'title',
        'type',
        'status'
    ];

    public function getStatusLabelAttribute()
    {
        $statusLabel = config('payroll.salary_component.status');

        return $this->status == 0 ? $statusLabel[0] : $statusLabel[1];
    }

    public function getSlugComponentAttribute()
    {
        return strtolower(str_replace(' ', '_', trim($this->title)));
    }

    public function scopeSlug($query)
    {
        return $query->where('status', '1');
    }
}
