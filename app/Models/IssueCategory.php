<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueCategory extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = str_slug($model->title);

            $latestName =
                static::whereRaw("slug = '$model->slug' or slug LIKE '$model->slug-%'")
                    ->latest('id')
                    ->value('slug');
            if ($latestName) {
                $pieces = explode('-', $latestName);

                $number = intval(end($pieces));

                $model->slug .= '-'.($number + 1);
            }
        });
    }
}
