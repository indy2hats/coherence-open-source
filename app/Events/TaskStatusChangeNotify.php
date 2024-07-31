<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStatusChangeNotify
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $assignees;
    public $task;
    public $reviewer;
    public $approvers;

    public function __construct(Task $task, $assignees, $reviewer, $approvers)
    {
        $this->assignees = $assignees;
        $this->task = $task;
        $this->reviewer = $reviewer;
        $this->approvers = $approvers;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
