<?php

namespace App\Listeners;

use App\Events\TaskEditNotify;
use App\Notifications\TaskEditNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;

class TaskEditNotifyListener  //implements ShouldQueue
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
     * @param  TaskEditNotify  $event
     * @return void
     */
    public function handle(TaskEditNotify $event)
    {
        if ($event->users->count()) {
            foreach ($event->users as $key => $user) {
                Notification::send($user, new TaskEditNotification($user, $event->task));
            }
        }
    }
}
