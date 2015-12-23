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
    return view('welcome');
});


Route::group(['middleware' => 'cors','prefix'=>'api'], function(){
    Route::resource('users', 'UserController');
    Route::post('auth/facebook', 'AuthController@facebook');
    Route::post('auth/login', 'AuthController@login');
    Route::post('auth/register', 'AuthController@register');
    Route::get('me', 'MeController@index');
    Route::resource('games', 'GamesController');
    Route::resource('sessions', 'SessionController');
});