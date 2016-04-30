<?php

namespace App\Events\User;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SignedUp extends UserEvent {
    protected $send_mail = true;
}
