<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /** The attributes that are mass assignable */
    protected $fillable = [

        'email',
        'company_name',
        'address',
        'phone',
        'city',
        'post_code',
        'country',
        'state',
        'currency',
        'image',
        'client_id',
        'user_id',
        //'vat_id',
        'vat_gst_tax_label',
        'vat_gst_tax_id',
        'vat_gst_tax_percentage',
        'account_manager_id'
    ];

    /** Get project details associated with a client  */
    public function project()
    {
        return $this->hasMany('App\Models\Project');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function account_manager()
    {
        return $this->belongsTo('App\Models\User', 'account_manager_id');
    }
}
