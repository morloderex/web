<?php

namespace App\Models\TrinityCore;

use App\Libraries\Hashing\TrinityCoreSha1Hasher;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = False;
    /**
     * @inheritdoc
     */
    protected $with = ['Tickets', 'Characters', 'Role'];
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'TrinityCore_auth';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'password', 'email', 'reg_mail', 'expansion'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'sha_pass_hash',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        static::saving(function($account){
            if($account->getAttribute('password'))
                unset($account->password);
        });
        parent::boot();
    }

    /**
     * One-to-One relationship with Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Role()
    {
        return $this->belongsTo(Role::class, 'id', 'id');
    }

    /**
     * One-To-Many Relationship with Character
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Characters()
    {
        return $this->hasMany(Character::class, 'guid', 'account');
    }

    /**
     * One-To-Many Relationship with Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Tickets()
    {
        return $this->hasMany(Ticket::class, 'playerGuid', 'guid');
    }

    /**
     * Resolved a User instance by Username
     * @param  Builder $query
     * @param  string $username
     * @return self
     */
    public function scopeFindByUsername($query, string $username) : User
    {
        return $query->where('username', '=', $username)->FirstOrFail();
    }

    /**
     * Gets the current password hash
     * @return string
     */
    public function getPasswordAttribute() : string
    {
        return $this->attributes['sha_pass_hash'];
    }

    /**
     * Eloquent Password Attribute Accessor
     * Sets sha_pass_hash field
     *
     * @param string $password
     * @return string
     */
    public function setPasswordAttribute(string $password)
    {
        $this->attributes['sha_pass_hash'] = $this->getEncryptedPassword($password);
        $this->attributes['password'] = $password;
    }

    /**
     * Encrypts the given password
     * @param  string $password
     * @return string
     */
    protected function getEncryptedPassword(string $password) : string
    {
        $username = $this->getAttribute('username');
        $hasher = new TrinityCoreSha1Hasher();
        return $hasher->make(compact('username', 'password'));
    }

    /**
     * Eloquent Username Attribute Accessor
     * Sets username field
     *
     * @param string $username
     * @return void
     */
    public function setUsernameAttribute(string $username)
    {
        $this->attributes['username'] = strtoupper($username);
    }

    /**
     * Eloquent Username Attribute Accessor.
     * Output a nicely formatted Username.
     *
     * @param  string $username
     * @return string
     */
    public function getUsernameAttribute(string $username) : string
    {
        return strtolower(ucfirst($username));
    }
}
