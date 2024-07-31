<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetTicketStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    /** The attributes that are mass assignable */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'is_inactive_asset',
        'is_close_issue',
        'is_allocate_asset',
        'created_at',
    ];

    protected $primaryKey = 'id';
}
