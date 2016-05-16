<?php

namespace App\Http\Controllers\Armory;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Emulators\TrinityCore\Account;
use App\Models\Emulators\TrinityCore\Character;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laracasts\Flash\Flash;

class CharacterController extends Controller
{
    protected $account;

    public function __construct(Guard $guard) {
        $this->middleware('auth');

        $user = $guard->user();

        if($user)
            $this->account = $user->accounts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = $this->account;

        return view('characters.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('armory.characters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->except('_token');
        $account = $this->findAccount($attributes);

        $this->authorize('store', $account);
        
        return $account->characters()->save(new Character($attributes)); 
    }

    protected function findAccount(array $attributes)
    {
        $account = $this->account;
        if ($account instanceof Collection) {
            $account = array_key_exists('accountID', $attributes) ?
                Account::find($attributes['accountID'])
                :
                $account->first();
        }
        return $account;
    }

    /**
     * Display the specified resource.
     *
     * @param  Character $character
     * @return \Illuminate\Http\Response
     */
    public function show(Character $character)
    {
        return view('armory.characters.show', compact('character'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Character $character
     * @return \Illuminate\Http\Response
     */
    public function edit(Character $character)
    {
        return view('armory.characters.edit', compact('character'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Character $character
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Character $character)
    {
        $this->authorize('update', $character);

        $attributes = $request->except('_token');

        $character = $character->update($attributes);
        $account = $this->findAccount($attributes);

        $updated = $account->Characters()->save($character);

        $redirect = redirect()->back();
        return $updated ? $redirect->withSuccess('Character updated.') : $redirect->withErrors('Something went wrong..');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Character $character
     * @return \Illuminate\Http\Response
     */
    public function destroy(Character $character)
    {
        $this->authorize('destroy', $character);

        $deleted = $character->delete();

        $redirect = redirect()->back();
        return $deleted ? $redirect->withSuccess('Character deleted.') : $redirect->withErrors('Something went wrong..');
    }

     public function addItem(Request $request)
     {
        $attributes = $request->except('_token');
        $account = $this->findAccount($attributes);

        if(in_array('character_guid', $attributes))
        {
            $character_guid = $attributes['character_guid'];
            $character = $account->characters()->each(function($character) use($character_guid, $attributes) {
                if($character->guid === $character_guid)
                {
                    return $character;
                }
            });
        } else {
            $character = $account->characters()->first();
        }

        $status = $character->save(new Item($attributes));

        if($status)
        {
            Flash::success('Item added to Character.');
        } else {
            Flash::error('Something went wrong...');
        }

        return redirect()->back();
    }
}
