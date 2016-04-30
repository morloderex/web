<?php

namespace App\Events\User;

use App\Events\Event;
use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

abstract class UserEvent extends Event
{
    public $action;

    public $user;

    /**
     * Whether to send a mail or not
     * @var bool
     */
    protected $send_mail = false;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        // Get the name of the Event, eg. SignedUp
        $action  = explode('\\', get_class($this));
        $action  = last($action);
        $action  = str_replace('_', ' ', $action);

        $this->action = $action;
}
