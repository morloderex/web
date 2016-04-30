<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;

use App\User;

class AuthTest extends TestCase
{
	use DatabaseTransactions, InteractsWithDatabase, InteractsWithAuthentication;
 	
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

    public function testCanCreateUser() {
    	$this->seed(UserTableSeeder::class);
    	$this->assertFalse(User::all()->isEmpty());
    }

    public function testCanLogin() {
    	$users = User::all();
		$user = ! $users->isEmpty() ? $users->random() : factory(User::class)->create();

    	$authenticated = Auth::login($user);
    	if($authenticated)
    	{
    		$this->seeIsAuthenticatedAs($user);
    		$this->CanActivate($user);
    	}

    	return False;
    }

    protected function CanActivate(User $user) {
    	$this->assertTrue($user->activate());
    }

    public function testCanLogout() {
    	$this->assertNull(Auth::logout());
    }
}
