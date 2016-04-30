<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;

// Leverage Laravel Service Container to inject Auth Facade
use Auth;

class UserPolicy
{
    use HandlesAuthorization;

    protected $validUser = False;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(User $user, $ability = null) {
        // Something to run at any given check
        // eg. a restrictive area or require a specific role, permission or whichever access control methodology does it for you :)
        
        $validUser = $this->validateUser($user);

        if($validUser)
            $this->validUser = $validUser;
    }

    protected function validateUser(User $user) {
        return (bool) $user->id === Auth::id();
    }

    public function update(User $user) {
        // Additional validation of User
        
        return $this->validUser;
    }

    public function delete(User $user) {
        // Additional validation of User   
        
        return $this->validUser;
    }

    public function destroy(User $user) {
        $this->delete($user);
    }

    public function create() {
        $locked = (bool)config('auth.locked');

        return $locked ?: $this->validUser;
    }
}
