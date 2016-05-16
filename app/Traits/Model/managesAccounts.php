<?php

namespace App\Traits\Model;


use App\Contracts\Auth\AccountManagerContract;
use App\Libraries\Auth\AccountManager;
use Illuminate\Contracts\Auth\Authenticatable;

trait managesAccounts
{
    use hasBootedEvent;
    /**
     * @var AccountManager
     */
    protected $accountManager;

    /**
     * Boot the trait
     * 
     * @return void
     */
    public static function bootManagesAccounts()
    {
        /**
         * Create event listener hooks.
         */
        static::booted(function($model){
            $manager = app(AccountManagerContract::class);
            $model->loadAccountManager($manager, $model);
        });
        
        static::deleting(function($model){
            $model->accountManager->destroyAccounts();
        });

        static::updating(function($model) {
            $model->accountManager->fillAccounts();
        });

        static::creating(function($model){
            $model->accountManager->mapAccountsRelatedByEmail();
            $model->accountManager->fillAccounts();
        });

        static::created(function ($model) {
            $model->accountManager->saveAccounts();
        });
    }

    public function loadAccountManager(AccountManager $manager, Authenticatable $model)
    {
        $manager->setUser($model);
        $this->accountManager = $manager;
    }
}