<?php

namespace App\Listeners;

use App\Events\UserTaskNotify;
use App\Notifications\UserTaskNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;

class UserTaskNotifyListener //implements ShouldQueue
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
    public function handle(UserTaskNotify $event)
    {
        if ($event->users->count()) {
            foreach ($event->users as $key => $user) {
                Notification::send($user, new UserTaskNotification($user, $event->task));
            }
        }
    }
}
