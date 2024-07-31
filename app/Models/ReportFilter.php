<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportFilter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'slug', 'report_name', 'project_ids', 'client_ids', 'session_type_ids'
    ];

    protected $casts = [
        'project_ids' => 'array',
        'client_ids' => 'array',
        'session_type_ids' => 'array'
    ];

    /**
     * Retrieve the associated user for this report filter.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo The user associated with this report filter.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
