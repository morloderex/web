<?php

namespace App\Libraries\Auth;
use App\Contracts\Emulators\AccountContract;
use App\Contracts\Auth\AccountManagerContract as Contract;
use App\Libraries\Hashing\TrinityCoreSha1Hasher;
use App\Models\Emulators\AbstractAccount;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * Manages game accounts.
 *
 * @pattern: Not sure.. leans towards a Repository pattern but does thing outside of that scope..
 * @suggestion: Refactor into a Repository that leverages the Event/Listener pattern.
 *
 * Class AccountManager
 * @package App\Libraries\Auth
 */
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
     * @var string
     */
    protected $password;

    /**
     * @var Collection
     */
    protected $accounts;

    /**
     * @var Hasher
     */
    protected $hasher;
    
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
                    switch($key)
                    {
                        case $key == 'model' && class_exists($value):
                            $this->mapSupportedAccountModel($value);
                            break;
                        
                        case $key == 'hasher':
                            $hasher = $value instanceof Hasher ? $value : app($value);
                            $this->setHasher($hasher);
                            break;
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
        
        if($this->isMappingEagerly())
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
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return AccountManager
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return Hasher
     */
    public function getHasher()
    {
        if(is_null($this->hasher))
        {
            $this->setHasher(
                app(
                    array_get(
                        $this->getOptions(),
                        'emulator.account.hashing.default'
                    )
                )
            );
        }

        return $this->hasher;
    }

    /**
     * @param Hasher $hasher
     * @return AccountManager
     */
    public function setHasher(Hasher $hasher)
    {
        $this->hasher = $hasher;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isMappingEagerly()
    {
        return $this->mapEagerly;
    }

    /**
     * @param boolean $mapEagerly
     * @return AccountManager
     */
    public function setMapEagerly(bool $mapEagerly)
    {
        $this->mapEagerly = $mapEagerly;
        return $this;
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
     * @return self
     */
    public function fillAccounts() : self
    {
        $user = $this->getUser();

        $this->accounts->each(function($account) use($user)
        {
            $hashAttributes = ['username' => $user->name, 'password' => $this->password];
            
            $account->username  = $user->name;
            $account->email     = $user->email;
            $account->password  = $this->getHasher()->make($hashAttributes);

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
        return $this;
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
            if($account->exists || $this->validateUsername($account->username))
            {
                if($relation instanceof BelongsToMany || $relation instanceof MorphMany)
                {
                    $relation->attach($account->id);
                } else {
                    $relation->associate($account);
                }
            } else {
                $relation->save($account);
            }
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function validateUsername(string $name = '') : bool
    {
        $name = !empty($name) ? $name : $this->getUser()->name;
        
        foreach ($this->accounts as $account)
        {
            $taken = !$account->where('username', $name)->get()->isEmpty();
            
            if($taken)
            {
                return False;
            }
        }
        
        return True;
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
        $relationMethod = array_has($options, 'relations.method') ? array_get($options, 'relations.method') : 'Accounts';

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