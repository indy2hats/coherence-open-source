<?php

namespace App\Providers;

use App\Events\TaskCreatorNotify;
use App\Events\TaskDeleteNotify;
use App\Events\TaskEditNotify;
use App\Events\TaskStatusChangeNotify;
use App\Events\UserCredentialShare;
use App\Events\UserTaskCommenReplyNotify;
use App\Events\UserTaskCommentNotify;
use App\Events\UserTaskMentionedNotify;
use App\Events\UserTaskNotify;
use App\Listeners\SendUserTaskMentionedNotification;
use App\Listeners\TaskCreatorNotifyListener;
use App\Listeners\TaskDeleteNotifyListener;
use App\Listeners\TaskEditNotifyListener;
use App\Listeners\TaskStatusChangeNotifyListener;
use App\Listeners\UserCredentialShareListener;
use App\Listeners\UserTaskCommentNotifyListener;
use App\Listeners\UserTaskCommentReplyNotifyListener;
use App\Listeners\UserTaskNotifyListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserTaskNotify::class => [
            UserTaskNotifyListener::class,
        ],
        UserTaskCommentNotify::class => [
            UserTaskCommentNotifyListener::class,
        ],
        UserTaskCommenReplyNotify::class => [
            UserTaskCommentReplyNotifyListener::class,
        ],
        TaskCreatorNotify::class => [
            TaskCreatorNotifyListener::class,
        ],
        TaskEditNotify::class => [
            TaskEditNotifyListener::class,
        ],
        TaskDeleteNotify::class => [
            TaskDeleteNotifyListener::class,
        ],
        TaskStatusChangeNotify::class => [
            TaskStatusChangeNotifyListener::class,
        ],
        UserCredentialShare::class => [
            UserCredentialShareListener::class,
        ],
        UserTaskMentionedNotify::class => [
            SendUserTaskMentionedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
