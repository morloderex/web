<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HasherTest extends TestCase
{
    protected $password = '0n3+tw0*th33-f0ur';

    public function testUser()
    {
        $user = $this->makeUser();
        $credentials = ['email' => $user->email, 'password' => $this->password];
        $authenticated = \Illuminate\Support\Facades\Auth::guard()->attempt($credentials);

        dump("authenticated? $authenticated");

        $result = $authenticated ?: $this->tryBuiltInAgainst($user);
        
        $this->assertTrue($result);
    }

    public function testLive()
    {
        $credentials = $this->getUserCredentials();

        $this
            ->visit('/')
            ->click('Login')
            ->seePageIs('/login')
            ->type($credentials['email'], 'email')
            ->type($credentials['password'], 'password')
            ->press('Login')
            ->seePageIs('/home');
    }

    protected function getUserCredentials()
    {
        $user = $this->makeUser();
        $credentials = ['email' => $user->email, 'password' => $this->password];

        return $credentials;
    }

    protected function makeUser()
    {
        $user = factory(\App\Models\User::class)->create();
        $user->password = $this->password;
        $user->save();
        
        return $user;
    }

    protected function tryBuiltInAgainst(\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        $encrypted = $user->getAuthPassword();
        
        $verified = password_verify($this->password, $encrypted);

        dump("verified? $verified");

        return $verified ?: password_hash($this->password, PASSWORD_BCRYPT, 10);
    }

}
