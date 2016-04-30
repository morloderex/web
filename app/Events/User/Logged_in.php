<?php

namespace App\Events\User;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Logged_in extends UserEvent {
  public function __construct(User $user)
  {
    // Assign ingame role to web role, if applicable.
    $user->setupAccountModel();
    $gmLevel = $user->getAccountModel()->getGMLevels();  
  }
}
