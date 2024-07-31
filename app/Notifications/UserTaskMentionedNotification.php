<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laravelista\Comments\Comment;

class UserTaskMentionedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Comment $comment;
    private User $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Comment $comment)
    {
        //
        $this->user = $user;
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $commenter = $this->comment->commenter;

        return (new MailMessage)->from(config('mail.from.address'), config('mail.from.name'))
            ->replyTo(config('mail.from.address'), config('mail.from.name'))
            ->subject($commenter->first_name.' '.$commenter->last_name.' mentioned you in a comment')
            ->view(
                'emails.taskCommentMentionNotify', ['user' => $this->user, 'comment' => $this->comment]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
