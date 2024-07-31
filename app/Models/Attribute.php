<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $with = ['attribute_values'];

    protected $fillable = [
        'name',
    ];

    /** Get values associated with an attribute */
    public function attribute_values()
    {
        return $this->hasMany('App\Models\AttributeValue');
    }
}
