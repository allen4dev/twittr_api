<?php

use Illuminate\Http\Request;

Route::prefix('auth')->group(function () {
  Route::post('/register', 'AuthController@register');
  Route::post('/login', 'AuthController@login');
});

Route::prefix('me')->group(function () {
  Route::get('/', 'ProfileController@show')->middleware('auth:api');
  Route::get('/tweets', 'ProfileController@index')->middleware('auth:api');
  Route::get('/favorites', 'ProfileFavoritesController@index')->middleware('auth:api');
});

Route::prefix('users')->group(function () {
  Route::get('/{user}', 'UserController@show');
});

Route::prefix('tweets')->group(function () {
  Route::post('/', 'TweetController@store')->middleware('auth:api');
  
  Route::get('/{tweet}', 'TweetController@show');
  Route::patch('/{tweet}', 'TweetController@update')->middleware('auth:api');
  Route::delete('/{tweet}', 'TweetController@destroy')->middleware('auth:api');

  Route::get('/{tweet}/favorited', 'TweetFavoritesController@show');

  Route::get('/{tweet}/replies', 'ReplyController@index');
  Route::post('/{tweet}/replies', 'ReplyController@store')->middleware('auth:api');

  Route::post('/{tweet}/favorite', 'FavoriteController@store')->middleware('auth:api');
  Route::delete('/{tweet}/unfavorite', 'FavoriteController@destroy')->middleware('auth:api');
});