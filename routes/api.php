<?php

use Illuminate\Http\Request;

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');

Route::get('/me', 'ProfileController@show');