<?php

namespace App\Listeners;

use App\Events\TaskCreatorNotify;
use App\Notifications\TaskCreatorNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;

class TaskCreatorNotifyListener  //implements ShouldQueue
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
     * @param  UserTaskNotify  $event
     * @return void
     */
    public function handle(TaskCreatorNotify $event)
    {
        if ($event->users->count()) {
            foreach ($event->users as $key => $user) {
                Notification::send($user, new TaskCreatorNotification($user, $event->task));
            }
        }
    }
}
