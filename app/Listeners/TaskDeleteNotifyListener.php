<?php

namespace App\Listeners;

use App\Events\TaskDeleteNotify;
use App\Notifications\TaskDeleteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;

class TaskDeleteNotifyListener  //implements ShouldQueue
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
     * @param  TaskDeleteNotify  $event
     * @return void
     */
    public function handle(TaskDeleteNotify $event)
    {
        if ($event->users->count()) {
            foreach ($event->users as $key => $user) {
                Notification::send($user, new TaskDeleteNotification($user, $event->task));
            }
        }
    }
}
