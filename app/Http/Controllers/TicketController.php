<?php

namespace App\Http\Controllers;

use App\Models\TrinityCore\Account;
use App\Models\TrinityCore\Ticket;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TicketController extends Controller
{
    protected $account;

    public function __construct(Guard $guard) {
        $this->middleware('auth');
        
        $this->account = $guard->user()->accounts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = $this->account;

        return view('tickets.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tickets.create');
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

        return $account->tickets()->save(new Ticket($attributes)); 
    }

    /**
     * Display the specified resource.
     *
     * @param  Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket = $ticket->update($request->except('_token'));
        $account = $this->findAccount($attributes);

        $updated = $account->Tickets()->save($ticket);

        $redirect = redirect()->back();
        return $updated ? $redirect->withSuccess('Ticket updated.') : $redirect->withErrors('Something went wrong..');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('destroy', $ticket);

        $deleted = $ticket->destroy();

        $redirect = redirect()->back();
        return $deleted ? $redirect->withSuccess('Ticket deleted.') : $redirect->withErrors('Something went wrong..');
    }

    protected function findAccount(array $attributes) {
        $account = $this->account;
        if($account instanceof Collection)
        {
            $account = array_key_exists('accountID', $attributes) ? Account::find($attributes['accountID']) ? $account->first();
        }
        return $account;
    }
}
