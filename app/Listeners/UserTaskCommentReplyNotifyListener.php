<?php

namespace App\Listeners;

use App\Events\UserTaskCommenReplyNotify;
use App\Notifications\TaskCommentReplyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Notification;

class UserTaskCommentReplyNotifyListener //implements ShouldQueue
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
     * @param  UserTaskComentNotify  $event
     * @return void
     */
    public function handle(UserTaskCommenReplyNotify $event)
    {
        $users = $event->comment->commentable->users->filter(function ($user) {
            return $user->status == 1;
        });

        $users->push($event->comment->commentable->task_creator);
        foreach ($users as $key => $user) {
            Notification::send($user, new TaskCommentReplyNotification($user, $event->comment));
        }
    }
}
