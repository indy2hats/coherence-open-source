<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBankDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'branch',
        'ifsc',
        'account_no',
        'user_id',
        'uan',
        'pan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
