<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
    ];

    /** Get values associated with an attribute */
    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute');
    }
}
