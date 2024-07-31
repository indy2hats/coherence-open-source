<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAttributeValue extends Model
{
    use HasFactory;
    protected $with = ['attribute_value'];
    protected $fillable = [
        'asset_id',
        'attribute_value_id',
    ];

    public function asset()
    {
        return $this->belongsTo('App\Models\Asset');
    }

    public function attribute_value()
    {
        return $this->belongsTo('App\Models\AttributeValue');
    }
}
