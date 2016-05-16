<?php

namespace App\Contracts\Auth;

use App\Contracts\Emulators\AccountContract;
use App\Models\User;

interface AccountManagerContract
{
    public function setUser(User $user);
    
    public function getUser();
    
    public function pushAccount(AccountContract $account);
    
    public function pushAccounts($accounts);
    
    public function fillAccounts();

    public function getAccounts();
    
    public function mapAccountsRelatedByEmail();

    public function hasAccounts();
    
    public function destroyAccounts();
}