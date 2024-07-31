<?php

namespace App\Listeners;

use App\Events\UserCredentialShare;
use App\Notifications\UserCredentialNotification;
use Notification;

class UserCredentialShareListener
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
     * @param  UserCredentialShare  $event
     * @return void
     */
    public function handle(UserCredentialShare $event)
    {
        if ($event->users->count()) {
            foreach ($event->users as $key => $user) {
                Notification::send($user, new UserCredentialNotification($user, $event->credential));
            }
        }
    }
}
