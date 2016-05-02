<?php

namespace App\Models;

use App\Models\TrinityCore\Account;
use App\Scopes\Traits\scopeRandom;
use App\Traits\Model\hasInformation;
use App\Traits\Model\hasPhotos;
use App\Traits\Model\Locatable;
use Hash;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
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
     * @var Collection | Account
     */
    protected $accounts;

    public Static function boot() {

        static::deleting(function($user){
            return $user->Accounts()->each(function($account){
                return $account->destroy();
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
            $this->validateUser();
            $user->saveAccounts();
        });
    }

    public function fillAndSetAccounts()
    {
        $this->accounts = $this->fillAccounts();
        return $this;
    }

    protected function mapMyAccounts()
    {
        $accounts = Account::whereEmail($this->email)->get();
        $accounts->each(function ($account) {
            $this->Accounts()->save($account);
        });
    }

    protected function fillAccounts()
    {
        $accounts = $this->Accounts;
        if ($accounts->isEmpty()) {
            $account = $this->createAccount($this);
            return $account;
        }

        return $accounts->each(function ($account) {
            $account->username = $this->name;
            $account->email = $this->email;
            $account->password = $this->password;
        });
    }

    protected function createAccount()
    {
        $attributes = [
            'username' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ];
        return new Account($attributes);
    }

    protected function encryptPassword()
    {
        $password = $this->password;
        $this->password = Hash::make($password);

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
        $accounts = $this->accounts;

        $relation = $this->Accounts();
        switch ($accounts) {
            case $accounts instanceof Collection:
                $relation->saveMany($accounts);
                break;

            case $accounts instanceof Account:
                $relation->save($accounts);
                break;

            default:
                dd(get_class($accounts));
                break;
        }
    }

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function Accounts() : BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'TrinityCore_web.accounts_user');
    }
}
