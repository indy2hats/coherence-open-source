<?php

namespace App\Models;

use Laravelista\Comments\Comment as BaseComment;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Comment extends BaseComment implements Searchable
{
    public function getSearchResult(): SearchResult
    {
        $url = route('tasks.show', $this->commentable_id);

        return new SearchResult(
            $this,
            $this->comment,
            $url
        );
    }
}
