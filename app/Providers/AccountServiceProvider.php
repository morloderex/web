<?php

namespace App\Providers;

use App\Contracts\Auth\AccountManagerContract;
use App\Libraries\Auth\AccountManager;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    protected $defer = True;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AccountManagerContract::class, function () {
            return new AccountManager;
        });
    }

    public function provides()
    {
        return [AccountManagerContract::class];
    }
}
