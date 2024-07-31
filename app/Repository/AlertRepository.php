<?php

namespace App\Repository;

use App\Models\UserWish;
use Illuminate\Support\Facades\DB;

class AlertRepository
{
    protected $model;

    public function __construct(UserWish $userWish)
    {
        $this->model = $userWish;
    }

    public function storeAndGetUserWishList()
    {
        $data = [
            'date' => request('date'),
            'title' => request('title'),
            'type' => request('type'),
            'user_id' => 0,
        ];

        if (request('type') != 'Text') {
            $file = request('file');

            $path = $file->store('user_wishes');

            $data += [
                'image' => $path,
            ];

            if (strstr($file->getMimeType(), 'image/')) {
                $data += [
                    'file_type' => 'Image',
                ];
            } elseif (strstr($file->getMimeType(), 'video/')) {
                $data += [
                    'file_type' => 'Video',
                ];
            }
        } else {
            $data += [
                'image' => request('file'),
                'file_type' => 'Text',
            ];
        }

        $this->model::create($data);

        $date = date('Y-m-d');

        if ($date == request('date')) {
            DB::select('update users set wish_notify=1 where 1');
        }

        return $this->model::get();
    }

    public function deleteAndGetUserWishList($id)
    {
        if ($this->model::find($id)->type != 'Text') {
            unlink('storage/'.$this->model::find($id)->image);
        }

        $this->model::find($id)->delete();

        return $this->model::get();
    }
}
