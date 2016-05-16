<?php

namespace App\Models;

use App\Libraries\Auth\AccountManager;
use App\Models\Emulators\TrinityCore\Account;
use App\Scopes\Traits\scopeRandom;
use App\Traits\Model\hasInformation;
use App\Traits\Model\hasPhotos;
use App\Traits\Model\Locatable;
use App\Traits\Model\managesAccounts;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
        hasPhotos,
        managesAccounts;
    
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

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class);
    }

    protected function setPasswordAttribute(string $password)
    {
        // store original
        $this->original['password'] = $password;
        // encrypt
        $this->attributes['password'] = Hash::make($password);

        return $this;
    }

    public function Accounts() : BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'TrinityCore_web.accounts_user');
    }
}
