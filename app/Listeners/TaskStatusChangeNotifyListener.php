<?php

namespace App\Listeners;

use App\Events\TaskStatusChangeNotify;
use App\Notifications\TaskStatusChangeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;

class TaskStatusChangeNotifyListener //implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TaskStatusChangeNotify $event)
    {
        if ($event->reviewer) {
            Notification::send($event->reviewer, new TaskStatusChangeNotification($event->reviewer, $event->task));
        }

        foreach ($event->assignees as $user) {
            Notification::send($user, new TaskStatusChangeNotification($user, $event->task));
        }

        foreach ($event->approvers as $user) {
            Notification::send($user, new TaskStatusChangeNotification($user, $event->task));
        }
    }
}
