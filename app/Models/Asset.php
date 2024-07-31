<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory;
    use SoftDeletes;

    /** The attributes that are mass assignable */
    protected $fillable = [
        'name',
        'purchased_date',
        'configuration',
        'serial_number',
        'value',
        'warranty',
        'vendor_id',
        'asset_type_id',
        'status',
        'created_at'
    ];

    protected $primaryKey = 'id';

    /** Get asset user details */
    public function assetUser()
    {
        return $this->hasMany(AssetUser::class, 'asset_id', 'id')->orderBy('id', 'DESC');
    }

    /** Get asset allocated user details */
    public function allocatedUser()
    {
        return $this->hasOne(AssetUser::class, 'asset_id', 'id')->where('status', 'allocated');
    }

    /** Get asset ticket details */
    public function assetTicket()
    {
        return $this->hasMany(AssetTicket::class, 'asset_id', 'id')->orderBy('id', 'DESC');
    }

    /** Get the user's full name by combining name and serial_number. */
    public function getFullNameAttribute()
    {
        return ucfirst($this->name).($this->serial_number ? '-'.$this->serial_number : '');
    }

    /** Get asset type  details associated with a asset */
    public function assetType()
    {
        return $this->belongsTo('App\Models\AssetType');
    }

    /** Get asset vendor  details associated with a asset */
    public function assetVendor()
    {
        return $this->belongsTo('App\Models\AssetVendor');
    }

    /** Get documents of asset */
    public function documents()
    {
        return $this->hasMany('App\Models\AssetDocument');
    }

    public function assetAttributeValues()
    {
        return $this->hasMany('App\Models\AssetAttributeValue');
    }
}
