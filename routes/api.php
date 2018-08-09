<?php

use Illuminate\Http\Request;

Route::prefix('auth')->group(function () {
  Route::post('/register', 'AuthController@register');
  Route::post('/login', 'AuthController@login');
});

Route::get('/me', 'ProfileController@show')->middleware('auth:api');

Route::prefix('users')->group(function () {
  
  Route::get('/{user}', 'UserController@show');

});

Route::prefix('tweets')->group(function () {

  Route::post('/', 'TweetController@store')->middleware('auth:api');

});