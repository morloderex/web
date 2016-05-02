<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Gate;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * @var string | null
     */
    protected $guard;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout', 'getRegister', 'postRegister']);
    }

    /**
     * Logs in using a random user
     * @return RedirectResponse
     * @throws notFoundHttpException
     */
    public function loginWithRandomUser() {
        if (App::environment('local', 'staging', 'development')) {
            $user = User::random();
            if($user instanceof Authenticatable)
                Auth::guard($this->getGuard())->login($user);
            
            return redirect('/home');
        } 
        
        abort(404);
    }

    /**
     * @overwrites: Trait RegistesUsers
     * 
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister() {
        $this->checkUserRegistration();
        return $this->showRegistrationForm();
    }

    protected function checkUserRegistration()
    {
        if (Gate::denies('create'))
            abort(403, 'User creation disabled.');
    }

    /**
     * @Overwrites: Trait RegistersUsers.
     * 
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request) {

        $this->checkUserRegistration();
        return $this->register($request);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            // Users created are by default not active
            // eg. require email confirmation or Administrative intervention.
            'active'    => Config('auth.user--active'),
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => $data['password'],
        ]);
    }
}
