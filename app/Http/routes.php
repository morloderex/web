<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('timer');
});

Route::get('/welcome', 'WelcomeController@getIndex');

Route::auth();

Route::get('faq', 'FaqController@index');

Route::get('/home', [
    'uses' => 'HomeController@index',
    'as' => 'home'
]);

Route::resource('user', UserController::class);

Route::group(['prefix' => 'armory', 'namespace' => 'Armory'], function () {
    Route::resource('item', ItemController::class);
    Route::resource('account', AccountController::class);
    Route::resource('character', CharacterController::class);
    Route::post('character/addItem', 'CharacterController@addItem');
});

Route::resource('photo', PhotoController::class);
Route::resource('gallery', GalleryController::class);

Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function() {

});

if(app()->environment('local', 'testing', 'development'))
{
    Route::get('/login/random', 'Auth\AuthController@loginWithRandomUser');
}