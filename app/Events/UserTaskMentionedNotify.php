<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Laravelista\Comments\Comment;

class UserTaskMentionedNotify
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    /**
     * The comment instance.
     *
     * @var Laravelista\Comments\Comment
     */
    public $comment;

    /**
     * The user instance.
     *
     * @var App\Models\User
     */
    public $users;

    /**
     * Create a new event instance.
     *
     * @param  Task  $task
     * @param  User[]  $users
     * @return void
     */
    public function __construct(Comment $comment, $users)
    {
        $this->comment = $comment;
        $this->users = $users;
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
