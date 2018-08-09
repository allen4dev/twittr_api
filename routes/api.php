<?php

use Illuminate\Http\Request;

Route::prefix('auth')->group(function () {
  Route::post('/register', 'AuthController@register');
  Route::post('/login', 'AuthController@login');
});

Route::get('/me', 'ProfileController@show')->middleware('auth:api');