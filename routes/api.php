<?php

use Illuminate\Http\Request;

Route::prefix('auth')->group(function () {
  Route::post('/register', 'AuthController@register');
  Route::post('/login', 'AuthController@login');
});

Route::prefix('me')->group(function () {
  Route::get('/', 'ProfileController@show')->middleware('auth:api');
  Route::post('/avatar', 'AvatarController@store')->middleware('auth:api');
  
  Route::get('/tweets', 'ProfileController@index')->middleware('auth:api');

  Route::get('/favorites/replies', 'ReplyFavoritesController@index')->middleware('auth:api');
  Route::get('/favorites/tweets', 'TweetFavoritesController@index')->middleware('auth:api');

  Route::get('/followers', 'ProfileFollowersController@index')->middleware('auth:api');
  Route::get('/followings', 'ProfileFollowingsController@index')->middleware('auth:api');

  Route::get('/activities', 'ActivityController@index')->middleware('auth:api')->name('activities');
  
  Route::get('/timeline', 'TimelineController@index')->middleware('auth:api');

  Route::get('/photos', 'UserPhotosController@index')->middleware('auth:api');

  Route::get('/notifications/unread', 'NotificationsController@index')->middleware('auth:api');
  Route::get('/notifications/{notification}', 'NotificationsController@show')->middleware('auth:api');
});

Route::prefix('users')->group(function () {
  Route::get('/{user}', 'UserController@show')->name('users.show');
  Route::get('/{user}/tweets', 'UserTweetsController@index');
  Route::get('/{user}/photos', 'UserPhotosController@show')->name('users.photos');
  Route::get('/{user}/followers', 'UserFollowersController@index');
  Route::get('/{user}/followings', 'UserFollowingsController@index');

  Route::post('/{user}/follow', 'FollowUserController@store')->middleware('auth:api');
  Route::delete('/{user}/unfollow', 'FollowUserController@destroy')->middleware('auth:api');
  
  Route::post('/{user}/photos', 'UserPhotosController@store');
});

Route::prefix('tweets')->group(function () {
  Route::get('/', 'TweetController@index');
  Route::post('/', 'TweetController@store')->middleware('auth:api');
  
  Route::get('/{tweet}', 'TweetController@show')->name('tweets.show');
  Route::patch('/{tweet}', 'TweetController@update')->middleware('auth:api');
  Route::delete('/{tweet}', 'TweetController@destroy')->middleware('auth:api');

  Route::post('/{tweet}/retweet', 'RetweetController@store')->middleware('auth:api');

  Route::get('/{tweet}/favorited', 'TweetFavoritesController@show');

  Route::get('/{tweet}/replies', 'ReplyController@index')->name('tweets.replies');
  Route::post('/{tweet}/replies', 'ReplyController@store')->middleware('auth:api');

  Route::post('/{tweet}/favorite', 'TweetFavoritesController@store')->middleware('auth:api');
  Route::delete('/{tweet}/unfavorite', 'TweetFavoritesController@destroy')->middleware('auth:api');
});

Route::prefix('replies')->group(function () {
  Route::get('/{reply}', 'ReplyController@show')->name('replies.show');
  Route::post('/{reply}/favorite', 'ReplyFavoritesController@store')->middleware('auth:api');
  Route::delete('/{reply}/unfavorite', 'ReplyFavoritesController@destroy')->middleware('auth:api');
});