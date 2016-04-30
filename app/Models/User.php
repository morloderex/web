<?php

namespace App\Models;

use App\Scopes\Traits\scopeRandom;

use App\Traits\Model\hasPhotos,
    App\Traits\Model\Locatable,
    App\Traits\Model\hasInformation;

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

    private $rawPassword;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public Static function boot() {
        static::saving(function($user){
            $password = $user->password;
            $user->password = Hash::make($password);
        });

        static::deleting(function($user){
            return $user->Accounts()->each(function($account){
                return $account->destroy();
            });
        });

        foreach (['created', 'updated'] as $event) {
            static::$event(function($user) {
                $this->updateAccounts($user);
            });
        }
    }

    protected function updateAccounts(User $user) {
        return $user->Accounts()->each(function($account) use($user){
                $account->username = $user->name;
                $account->email    = $user->email;
                $account->password = $user->password;

                $account->save();
        });
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function Accounts() {
        return $this->belongsToMany(Account::class);
    }
}
