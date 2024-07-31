<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Laravelista\Comments\Comment;

class UserTaskCommentNotify
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * comment collection.
     *
     * @var Task
     */
    public $comment;

    /**
     * Create a new event instance.
     *
     * @param  Comment  $comment
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
