<?php

namespace App\Http\Controllers\Armory;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\TrinityCore\Account;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class AccountController extends Controller
{
    protected $user;

    public function __construct(Guard $guard) {
        
        $this->middleware('auth');

        $this->user = $guard->user();
    }

    public function index()
    {
        $accounts = $this->getAccounts();
        return view('armory.accounts.index', compact('accounts'));
    }

    protected function getAccounts() {
        return $this->user->accounts();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('armory.accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $account = new Account($request->all());

        $this->authorize('store', $account);

        $status = $this->user->accounts()->save($account);
        if (!$status) {
            $this->flashErrorAndRedirectBack();
        } else {
            Flash::success('account created!');
            return redirect()->route('home');
        }

    }

    protected function flashErrorAndRedirectBack()
    {
        Flash::error('Something went wrong...');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  Account $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        return view('armory.accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Account $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        return view('armory.accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Account $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        $this->authorize('update', $account);

        $account = $account->update($request->except('_token'));

        $updated = $account->Accounts()->save($account);

        if (!$updated) {
            $this->flashErrorAndRedirectBack();
        } else {
            Flash::success('Account Updated.');
            return redirect()->route('home');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Account $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $this->authorize('destroy', $account);

        $deleted = $account->delete();

        if (!$deleted) {
            $this->flashErrorAndRedirectBack();
        } else {
            Flash::success('Account Deleted.');
            return redirect()->route('home');
        }
    }
}
