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
     * Works, but isn't elegant at all...
     *
     * overrides the eloquent bootIfNotBooted() method..
     */
    protected function bootIfNotBooted()
    {
        parent::bootIfNotBooted();

        //$manager = app(AccountManagerContract::class);

        $manager = new AccountManager;
        $this->loadAccountManager($manager,$this);
    }

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

        /**
         * Doesn't work

        static::booted(function($model){
            $manager = app(AccountManager::class);
            $model->loadAccountManager($manager, $model);
        });
        **/

        static::deleting(function($model){
            $model->accountManager->destroyAccounts();
        });

        static::updating(function($model) {
            if($model->validUpdate())
            {
                $model->accountManager->fillAccounts();
            }
        });
        
        static::updated(function($model){
            if($model->validUpdate())
            {
                $model->accountManager->saveAccounts();
            }
        });
        
        static::creating(function($model){
            $model->accountManager->mapAccountsRelatedByEmail();
            $model->accountManager->fillAccounts();
        });

        static::created(function ($model) {
            $model->accountManager->saveAccounts();
        });
    }

    /**
     * @TODO: Improve.. :D
     *
     * @return bool
     */
    protected function validUpdate()
    {
        // get whats changed,
        // because models likes to get dirty.. derp.
        $changed = $this->getDirty();

        // if whats changed, is a token or an updated timestamp, skip.
        $invalidUpdate = (array_key_exists('remember_token', $changed) || array_key_exists('updated_at',$changed));

        // Not an invalid Update..
        // much smell, such code.. yes.
        return !$invalidUpdate;
    }

    public function loadAccountManager(AccountManager $manager, Authenticatable $model)
    {
        $manager->setUser($model);
        $this->accountManager = $manager;
        
        return $this;
    }
}