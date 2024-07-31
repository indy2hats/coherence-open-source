<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTypeAttribute extends Model
{
    use HasFactory;

    /** The Asset Type Attributes that are mass assignable */
    protected $fillable = [
        'asset_type_id',
        'attribute_id',
    ];

    protected $primaryKey = 'id';

    // Define the relationship with Attribute
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    // Define the relationship with AssetType
    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
