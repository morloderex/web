<?php

namespace App\Http\Controllers;

use App\Contracts\Auth\AccountManagerContract;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Laracasts\Flash\Flash;

class UserController extends Controller
{
    protected  $accountManager;

    public function __construct(AccountManagerContract $accountManager)
    {
        $this->middleware('auth');
        $this->accountManager = $accountManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Count users

        // information

        // statistics
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->to('/register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect()->to('/register')->withInput($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('edit', $user);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $attributes = $request->except('_token');
        $updated = $user->update($attributes);

        if($updated)
        {
            $user->Accounts->each(function($account) use($attributes) {
               $account->update($attributes); 
            });
        }

        $this->flashMessage(compact('updated'));

        return redirect()->route('user.show', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        
        $user->Accounts->each(function($account){
            $account->delete();
        });
        
        $deleted = $user->delete();

        $this->flashMessage(compact('deleted'));

        return redirect()->url('user.index');
    }

    protected function flashMessage(array $action)
    {
        $what = array_first(
            array_keys($action)
        );
        $succeeded = (bool)array_first(
            array_values($action)
        );
        if($succeeded)
        {
            Flash::success("User and associated Accounts, $what.");
        } else {
            Flash::error("failed to $what.");
        }
    }
}
