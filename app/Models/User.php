<?php

namespace App\Models;

use App\Models\TrinityCore\Account;
use App\Scopes\Traits\scopeRandom;
use App\Traits\Model\hasInformation;
use App\Traits\Model\hasPhotos;
use App\Traits\Model\Locatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Mpociot\Teamwork\Traits\UserHasTeams;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles,
        hasInformation,
        UserHasTeams,
        scopeRandom,
        Locatable,
        hasPhotos;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $casts = [
        'name'      =>  'string',
        'email'     =>  'string',
        'posts'     =>  'collection',
        'roles'     =>  'collection',
        'teams'     =>  'collection',
        'invites'   =>  'collection'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var Collection
     */
    protected $accountCollection;

    /**
     * @var Collection
     */
    protected $resolvedAccountsCollection;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);

        $this->accountCollection = new Collection;
        $this->resolvedAccountsCollection = new Collection;
    }

    public Static function boot() {

        parent::boot();
        
        static::deleting(function($user){
            return $user->Accounts()->each(function($account){
                return $account->delete();
            });
        });

        static::updating(function($user) {
            $user->fillAndSetAccounts();
            $user->encryptPassword();
        });

        static::creating(function($user){
            $user->mapMyAccounts();
            $user->fillAndSetAccounts();

            $user->encryptPassword();
        });

        static::created(function ($user) {
            $user->validateUser();
            $user->saveAccounts();
        });
    }

    public function fillAndSetAccounts()
    {
        $this->accountCollection->push(
            $this->fillAccounts()
        );
        return $this;
    }

    protected function fillAccounts()
    {
        $accounts = $this->Accounts;
        if ($accounts->isEmpty()) {
            $account = $this->createAccount();
            return $account;
        }

        return $accounts->each(function ($account) {
            $account->username  = $this->name;
            $account->email     = $this->email;
            $account->password  = $this->password;
        });
    }

    protected function createAccount()
    {
        $attributes = [
            'username'  => $this->name,
            'email'     => $this->email,
            'password'  => $this->password
        ];
        return new Account($attributes);
    }

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class);
    }

    protected function mapMyAccounts()
    {
        $accounts = Account::whereEmail($this->email)->get();
        $this->resolvedAccountsCollection->push($accounts);

        return $this;
    }

    protected function encryptPassword()
    {
        // grab unencrypted password
        $password = $this->password;
        // encrypt and override
        $this->password = Hash::make($password);

        //$this->password = bcrypt($password);

        return $this;
    }

    protected function validateUser() {
        if( ! isset($this->id) )
        {
            $this->save();
        }

        return True;
    }

    protected function saveAccounts()
    {
        $accounts = $this->accountCollection;
        $resolvedAccounts = $this->resolvedAccountsCollection;

        $relation = $this->Accounts();

        // First, save the newly created accounts
        $accounts->each(function ($account) use ($relation) {
            if ($account instanceof Collection) {
                $relation->saveMany($account);
            } else {
                $relation->save($account);
            }
        });

        // Then associate the ones that already existed
        $resolvedAccounts->each(function($account) use ($relation) {
            if ($account instanceof Collection) {
                $account->each(function($child) use($relation) {
                   $relation->associate($child)->save();
                });
            } else {
                $relation->associate($account)->save();
            }
        });
    }

    public function Accounts() : BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'TrinityCore_web.accounts_user');
    }
}
