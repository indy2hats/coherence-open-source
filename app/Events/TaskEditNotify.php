<?php

namespace App\Events;

use App\Models\Task;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskEditNotify
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * users collection.
     *
     * @var User
     */
    public $users;

    /**
     * tasks collection.
     *
     * @var Task
     */
    public $task;

    /**
     * Create a new event instance.
     *
     * @param  Task  $task
     * @param  User[]  $users
     * @return void
     */
    public function __construct($users, Task $task)
    {
        $this->users = $users;
        $this->task = $task;
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
