<?php

namespace App\Models;

use App\Models\TrinityCore\Account;
use App\Scopes\Traits\scopeRandom;

use App\Traits\Model\hasPhotos,
    App\Traits\Model\Locatable,
    App\Traits\Model\hasInformation;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Mpociot\Teamwork\Traits\UserHasTeams;

use Spatie\Permission\Traits\HasRoles;

use Hash;

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

    public Static function boot() {
        static::saving(function($user) {
            $password = $user->password;
            $user->fillAccounts($user);
            
            $user->password = Hash::make($password);
        });

        static::deleting(function($user){
            return $user->Accounts()->each(function($account){
                return $account->destroy();
            });
        });

        static::updating(function($user) {
            $user->fillAccounts($user);
        });

        static::creating(function($user){
            $user->mapMyAccounts();
            $user->fillAccounts($user);
        });
    }

    protected function fillAccounts(User $user) {
        return $user->Accounts()->each(function($account) use($user){
                $account->username = $user->name;
                $account->email    = $user->email;
                $account->password = $user->password;

                $account->save();
        });
    }

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class);
    }

    protected function mapMyAccounts()
    {
        $accounts = Account::whereEmail($this->email)->get();
        $accounts->each(function($account){
            $this->Accounts()->save($account);
        });
    }

    public function Accounts() : BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'TrinityCore_web.accounts_user');
    }
}
