<?php

namespace App\Events\Role;

use App\Events\Event;

use App\Models\Emulators\TrinityCore\Account,
    App\Models\Emulators\TrinityCore\Role;

class Revoked extends Event
{
    public $message;

    public $account;
    public $role;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Account $target, Role $role)
    {
        $this->account = $target;
        $this->role    = $role;
        
        $this->message = $target->name . ' has been demoted to ' . $role->name . '!';
    }
}
