<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->name = str_slug($model->display_name); // change the ToBeSluggiefied

            $latestSlug =
                static::whereRaw("name = '$model->name' or name LIKE '$model->name-%'")
                ->latest('id')
                ->value('name');
            if ($latestSlug) {
                $pieces = explode('-', $latestSlug);

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
