<?php

namespace App\Http\Controllers\Armory;

use Illuminate\Http\Request;

use App\Http\Requests;

class AccountController extends Controller
{
    protected $user;

    public function __construct(Guard $guard) {
        
        $this->middleware('auth');

        $this->user = $guard->user();
    }

    public function welcome() {
        $accounts = $this->getAccounts();
        return view('armory.accounts.index', compact('accounts'));
    }

    protected function getAccounts() {
        return $this->user->accounts();
    }
}
