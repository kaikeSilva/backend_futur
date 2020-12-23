<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
], function () {

    Route::group([
        'middleware' => 'auth',
    ], function () {
        
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');

        
        
    });

    //Rotas publicas
    Route::get('users', 'UserController@index')->name('users.index'); 
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
});


