<?php

namespace App\Listeners;

use App\Events\UserTaskMentionedNotify;
use App\Notifications\UserTaskMentionedNotification;
use Notification;

class SendUserTaskMentionedNotification
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
     * @param  UserTaskMentionedNotify  $event
     * @return void
     */
    public function handle(UserTaskMentionedNotify $event)
    {
        $users = $event->users;
        foreach ($users as $key => $user) {
            Notification::send($user, new UserTaskMentionedNotification($user, $event->comment));
        }
    }
}
