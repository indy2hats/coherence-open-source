<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetUser extends Model
{
    use HasFactory;

    /** The attributes that are mass assignable */
    protected $fillable = [
        'user_id',
        'asset_id',
        'assigned_date'
    ];

    protected $primaryKey = 'id';

    /** Get user details associated with a asset */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /** Get asset details */
    public function asset()
    {
        return $this->belongsTo('App\Models\Asset');
    }
}
