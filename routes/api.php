<?php

use App\User;
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

        //Rotas user
        Route::get('users', 'UserController@index')->name('users.index'); 
        Route::get('users/detail', 'UserController@show')->name('users.show'); 
        //Route::put('users/update', 'UserController@index')->name('users.index'); 
        // Route::get('users', 'UserController@index')->name('users.index'); 

        //rotas de cursos
        Route::get('courses','CourseController@index')->name('courses.index');
        Route::post('courses','CourseController@store')->name('courses.store');
        Route::get('courses/{id}','CourseController@show')->name('courses.show');
        Route::put('courses/{id}','CourseController@update')->name('courses.update');
        Route::delete('courses/{id}','CourseController@destroy')->name('courses.destroy');

        //rotas de metas
        Route::get('goals','GoalController@index')->name('goals.index');
        Route::post('goals','GoalController@store')->name('goals.store');
        Route::get('goals/{id}','GoalController@show')->name('goals.show');
        Route::put('goals/{id}','GoalController@update')->name('goals.update');
        Route::delete('goals/{id}','GoalController@destroy')->name('goals.destroy');

        Route::put('goal-items/{id}','GoalItemController@update')->name('goal-items.update');
    });

    //Rotas publicas
    
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
});


