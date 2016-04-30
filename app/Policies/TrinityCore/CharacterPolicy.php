<?php

namespace App\Policies\TrinityCore;

use Illuminate\Auth\Access\HandlesAuthorization;

class CharacterPolicy
{
    use HandlesAuthorization;

    protected $validCharacter = False;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(Character $character, $ability = null) {
        // Something to run at any given check
        // eg. a restrictive area or require a specific role, permission or whichever access control methodology does it for you :)
        
        $validCharacter = $this->validateAccountFor($character);

        if($validCharacter)
            $this->validCharacter = $validCharacter;
    }

    protected function validateAccountFor(Character $character) {
        return (bool) Auth::user()->Accounts->contains(function($key, $value) use($character) {
            if($key == 'id')
            {
                return $value == $character->account->id;
            }
        }); 
    }

    public function update(Character $character) {        
        // Additional validation of Character   
        
        return $this->validCharacter;
    }

    public function delete(Character $character) {
        // Additional validation of Character   
        
        return $this->validCharacter;
    }

    public function destroy(Character $character) {
        return $this->delete($character);
    }

    public function store() {
        return $this->validCharacter;
    }
}
