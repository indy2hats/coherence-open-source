<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    /** Get role associated with user */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function userList()
    {
        return $this->hasMany('App\Models\User');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->name = str_slug($model->display_name);

            $latestName =
                static::whereRaw("name = '$model->name' or name LIKE '$model->name-%'")
                ->latest('id')
                ->value('name');
            if ($latestName) {
                $pieces = explode('-', $latestName);

                $number = intval(end($pieces));

                $model->name .= '-'.($number + 1);
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }
}
