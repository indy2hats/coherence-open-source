<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTicket extends Model
{
    use HasFactory;

    /** The attributes that are mass assignable */
    protected $fillable = [
        'user_id',
        'asset_id',
        'asset_user_id',
        'issue',
        'type',
        'created_at'
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

    /** Get asset ticket details */
    public function assetUser()
    {
        return $this->belongsTo('App\Models\AssetUser');
    }

    /** Get asset ticket status details */
    public function ticketStatus()
    {
        return $this->belongsTo('App\Models\AssetTicketStatus', 'status_id', 'id');
    }
}
