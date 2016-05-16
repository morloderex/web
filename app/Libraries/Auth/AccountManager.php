<?php

namespace App\Libraries\Auth;
use App\Contracts\Emulators\AccountContract;
use App\Contracts\Auth\AccountManagerContract as Contract;
use App\Models\Emulators\AbstractAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class AccountManager implements Contract
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Collection
     */
    protected $accounts;

    /**
     * @var bool
     */
    protected $mapEagerly = false;

    public function __construct(array $options = [])
    {
        $this->accounts = new Collection;

        if(empty($options))
        {
            $options = config('AccountManager');
        }

        $this->setOptions($options);
        $this->mapOptions();
    }

    /**
     * @param array $options
     */
    protected function mapOptions()
    {
        foreach ($this->getOptions() as $key => $value) {
            if(property_exists($this, $key))
            {
                $this->$key = $value;
            }
            
            if($key == 'emulator' && is_array($value))
            {
                if(array_has($value, 'core.supported'))
                {
                    $this->mapSupportedAccountModels($value);
                }
            }
        }
    }

    /**
     * @param array $options
     */
    protected function mapSupportedAccountModels(array $options)
    {
        foreach (array_get($options, 'core.supported') as $core => $config) {
            if(is_array($config) || $config instanceof \Traversable)
            {
                foreach ($config as $key => $value) {
                    if($key == 'model' && class_exists($value))
                    {
                        $this->mapSupportedAccountModel($value);
                    }
                }
            } else {
                // no configuration given. Try to resolve model, if it exists, use it.
                $model = "App\\Models\\Emulator\\$core\\Account";
                if(class_exists($model))
                {
                    $this->mapSupportedAccountModel($model);
                } else {
                    throw new \InvalidArgumentException(40, "config for [AccountManager.emulator.core.supported.$core] defined but model was not found.");
                }
            }
        }
    }

    protected function mapSupportedAccountModel($model)
    {
        if(is_string($model))
        {
            $model = app($model);
        }

        if( ! $model instanceof AbstractAccount)
        {
            $class = get_class($model);
            throw new \InvalidArgumentException(500, "expected instance of AbstractAccount, got $class.");
        }

        $this->pushAccount($model);
    }

    /**
     * @param User $model
     * @return AccountManager
     */
    public function setUser(User $model) : self
    {
        $this->user = $model;
        
        if($this->mapEagerly)
        {
            $this->mapAccountsRelatedByEmail();
        }
        
        return $this;
    }

    /**
     * @return User
     */
    public function getUser() : User
    {
        // @TODO: some validation
       return $this->user;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options = [])
    {
        $original = $this->options;
        if( ! empty($original) )
        {
            $options = array_merge($options, $original);
        }

        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * @param $accounts
     * @return AccountManager
     */
    public function pushAccounts($accounts) : self
    {
        $this->validateAccounts($accounts);

        foreach ($accounts as $account) {
            $this->pushAccount($account);
        }

        return $this;
    }

    /**
     * @param AccountContract $account
     * @return AccountManager
     */
    public function pushAccount(AccountContract $account) : self
    {
        $this->accounts->push($account);
        return $this;
    }

    /**
     * @return void
     */
    public function fillAccounts()
    {
        $user = $this->getUser();

        $this->accounts->each(function($account) use($user)
        {
            $account->username  = $user->name;
            $account->email     = $user->email;
            // grab original password, before any mutation or altering.
            $account->password  = $user->getOriginal('password');

            $options = $this->getOptions();
            // check if any predefined attributes exists..
            // this could be something along the lines of an expansion, a recruiter or even a Role. 
            if(array_has($options,'emulator.account.attributes'))
            {
                foreach (array_get($options,'emulator.account.attributes') as $attribute => $value) {
                    if($attribute == 'username' || $attribute == 'email' || $attribute == 'password')
                    {
                        // if any of above stated attributes found, skip it.
                        continue;
                    }

                    $account->$attribute = $value;
                }
            }
        });
    }

    /**
     * @return AccountManager
     */
    public function mapAccountsRelatedByEmail() : self
    {
        $email = $this->getUser()->email;
        
        foreach ($this->accounts as $account)
        {
            $accounts = $account->where('email', $email)->get();
            
            if( ! $accounts->isEmpty() )
            {
                $this->pushAccounts($accounts);
            }
        }
        
        return $this;
    }

    /**
     * @TODO: refactor, method name is misleading.
     * 
     * @return void
     */
    public function saveAccounts()
    {
        $accounts = $this->accounts;
        $relation = $this->getAccountsRelation();
        
        foreach($accounts as $account)
        {
            if($account->exists)
            {
                $relation->associate($account);
            } else {
                $relation->save($account);
            }
        }
    }


    /**
     * @TODO: refactor, method name is misleading.
     * 
     * @return Collection
     */
    public function getAccounts() : Collection
    {
        return $this->getAccountsRelation(True);
    }

    /**
     * @TODO: refactor, method name is misleading.
     * 
     * @return boolean
     */
    public function hasAccounts() : bool
    {
        return $this->getAccountsRelation(True)->isEmpty();
    }

    /**
     * @TODO: refactor, method name is misleading.
     * 
     * @return bool
     */
    public function destroyAccounts() : bool
    {
        // @todo: validate or move relationship table to config
        $relatedAccountIds = $this->getAccountsRelation()->lists('user_accounts.account_id');
        // Leverages database macros
        return Relation::whereIn($relatedAccountIds)->delete();
    }

    /**
     * @param bool $returnCollection
     * @return mixed
     */
    protected function getAccountsRelation(bool $returnCollection = False)
    {
        $user = $this->getUser();
        $options = $this->getOptions();
        $relationMethod = array_has($options, 'relations.method') ? array_get($options, 'relations.method') : 'accounts';

        // Leverage attribute accessor to return the collection if requested.
        return $returnCollection ? $user->$relationMethod : $user->$relationMethod();
    }

    /**
     * Validates
     *
     * @param $accounts
     */
    protected function validateAccounts($accounts)
    {
        if( ! is_array($accounts) && ! $accounts instanceof \Traversable)
        {
            $type = gettype($accounts);
            throw new \InvalidArgumentException("accounts must be of type array or implement Traversable, $type given.");
        }
    }

}