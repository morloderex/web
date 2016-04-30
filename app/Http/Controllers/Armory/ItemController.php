<?php

namespace App\Http\Controllers\Armory;

use App\Models\Character\Item;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    protected $account;

    public function __construct(Guard $guard) {
        $this->middleware('auth');

        $this->account = $guard->user->accounts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = $this->account;
        return view('armory.armory.item.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('armory.armory.item.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = new Item($request->all());
        $this->authorize('store', $item);

        $saved = $item->save();
        if( ! $saved )
        {
            $this->flashErrorAndRedirectBack();   
        }

        Flash::success('Item stored successfully!');
        return redirect()->route('armory.item.show', $item->entry);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return View('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('edit', $item);

        $updated = $item->update($request->all());

        if( ! $updated )
        {
            $this->flashErrorAndRedirectBack();
        }

        Flash::success('Item updated successfully');
        return redirect()->route('armory.item.show', $item->entry);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $this->authorize('destroy', $item);

        $destroyed = $item->destroy();

        if( ! $destroyed )
        {
            $this->flashErrorAndRedirectBack();
        }

        Flash::success('Item destroyed.');
        return redirect()->route('armory.item.index');
    }

    protected function findAccount(array $attributes) {
        $account = $this->account;
        if($account instanceof Collection)
        {
            $account = array_key_exists('accountID', $attributes) ? 
                Account::find($attributes['accountID']) 
                :
                $account->first();
        }
        return $account;
    }

    protected function flashErrorAndRedirectBack()
    {
        Flash::error('Something went wrong...');
        return redirect()->back();
    }
}
