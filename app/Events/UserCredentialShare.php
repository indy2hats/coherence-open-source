<?php

namespace App\Events;

use App\Models\ProjectCredentials;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCredentialShare
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * users collection.
     *
     * @var User
     */
    public $users;

    /**
     * credential collection.
     *
     * @var ProjectCredentials
     */
    public $credential;

    /**
     * Create a new event instance.
     *
     * @param  ProjectCredentials  $credential
     * @param  User[]  $users
     * @return void
     */
    public function __construct(ProjectCredentials $credential, $users)
    {
        $this->users = $users;
        $this->credential = $credential;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
