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

  Route::get('/', 'WelcomeController@getIndex');

  Route::auth();

  Route::get('/login/random', 'Auth\AuthController@loginWithRandomUser');

  Route::get('/home', 'HomeController@index');

  Route::resource('user', UserController::class);

    Route::group(['prefix' => 'armory', 'namespace' => 'Armory'], function () {
            Route::resource('item', ItemController::class);
            Route::resource('account', AccountController::class);
            Route::resource('character', CharacterController::class);
            Route::post('character/addItem', 'CharacterController@addItem');
    });
  
  Route::resource('post', PostController::class);
  Route::resource('photo', PhotoController::class);
  Route::resource('category', CategoryController::class);

  Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function() {
    
  });
