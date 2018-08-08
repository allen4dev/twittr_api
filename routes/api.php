<?php

use Illuminate\Http\Request;

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');