<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;

use App\Models\User;

class AuthTest extends TestCase
{
	use DatabaseTransactions, InteractsWithDatabase, InteractsWithAuthentication;

    protected $password = '0n3+tw0*th33-f0ur';

    /**
     * Default preparation for each test
     */
    public function setUp()
    {
        parent::setUp();

        $this->prepareForTests();
    }

    /**
     * Migrates the database, in case its empty.
     * Start database Transaction, this will ensure none of the generated bogus during test persists.
     */
    private function prepareForTests()
    {
        \Illuminate\Support\Facades\Artisan::call('migrate');
        $this->beginDatabaseTransaction();
    }

    public function testCanSeedUsers() {
    	$this->seed(UserTableSeeder::class);
    	$this->assertFalse(User::all()->isEmpty());
    }

    public function testAuthentication() {
    	$users = User::all();
		$user = ! $users->isEmpty() ? $users->random() : factory(User::class)->create();

    	$authenticated = Auth::login($user);
    	if($authenticated)
    	{
    		$this->seeIsAuthenticatedAs($user);
            $this->assertTrue($user->activate());
            $this->assertNull(Auth::logout());
        }

    	return False;
    }

    public function testCanRegister()
    {
        $details = $this->getUserDetails();

        $this->visit('/register')
             ->seePageIs('/register')
             ->type($details['name'], 'name')
             ->type($details['email'], 'email')
             ->type($details['password'], 'password')
             ->type($details['password'], 'password_confirmation')
             ->press('Register')
             ->seePageIs('/home')
             ->seeIsAuthenticated();
    }

    public function testCanLogin()
    {
        $details = $this->getUserDetails();

        $this
            ->visit('/')
            ->click('Login')
            ->seePageIs('/login')
            ->type($details['email'], 'email')
            ->type($details['password'], 'password')
            ->press('Login')
            ->seePageIs('/home')
            ->seeIsAuthenticated();
    }


    public function testCanLogout()
    {
        $this->seeIsAuthenticated();
        
        $this->visit('/')
             ->click('/Logout')
             ->dontSeeIsAuthenticated();
    }
    

    protected function getUserDetails()
    {
        $user = $this->makeUser();
        $details = $user->toArray();
        $details['password'] = $this->password;

        return $details;
    }

    protected function makeUser()
    {
        $user = factory(\App\Models\User::class)->create();
        $user->password = $this->password;
        $user->save();

        return $user;
    }

}
