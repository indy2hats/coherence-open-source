<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetVendor extends Model
{
    use HasFactory;
    use SoftDeletes;

    /** The attributes that are mass assignable */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'description',
        'status',
        'created_at'
    ];

    protected $primaryKey = 'id';
}
