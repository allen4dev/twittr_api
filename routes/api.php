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

  Route::get('/{tweet}', 'TweetController@show');
  Route::post('/', 'TweetController@store')->middleware('auth:api');

  Route::post('/{tweet}/replies', 'ReplyController@store');
});