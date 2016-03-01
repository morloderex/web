<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        static::saving(function ($user){
           $user->dispatchAPIRequest('create', $user);
        });

        static::updating(function ($user){
            $user->dispatchAPIRequest('update', $user);
        });

        static::deleting(function ($user){
            $user->dispatchAPIRequest('delete', $user);
        });
    }

    protected function dispatchAPIRequest(string $request, User $user)
    {
        // Move into seperate class(service?), create service provider, grab token.
        $url = 'http://emuapi.dev/account';
        $client = new Client();

        switch($request)
        {
            case 'create':
                $client = $client->request('POST', $url, [
                    'json' => [
                        'username'  => $user->name,
                        'email'     => $user->email,
                        'password'  => $user->password
                    ]
                ]);
                break;

            case 'update':
                $client = $client->request('PATCH', "$url/$user->id", [
                    'json' => $this->findUpdated($user)
                ]);
                break;

            case 'delete':
                $client = $client->request('DELETE', $url.'/'.$user->id);
                break;
        }
        return $client->getStatusCode() === 200;
    }

    /**
     * Filters the updated attributes
     * this should be called during ['updating', 'updated'] event
     *
     * @return array
     */
    public function findUpdated(User $user)
    {
        $updated = [];
        foreach ($user->getDirty() as $attribute => $value) {
            if($value !== $user->getOriginal($attribute))
                $updated[$attribute] = $value;
        }
        return $updated;
    }
}
