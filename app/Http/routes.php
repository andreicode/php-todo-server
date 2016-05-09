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


Route::group(['middleware' => 'cors'], function(){

  Route::post('login', 'AuthenticateController@login');
  Route::post('register', 'AuthenticateController@register');

  Route::group(['middleware' => 'jwt.auth'], function(){

    Route::group(['prefix' => 'list'], function(){
      Route::get('/', 'ListController@index');
      Route::post('store', 'ListController@store');
      Route::get('{id}', 'ListController@view');
      Route::post('{id}/edit','ListController@edit');
      Route::post('{id}/delete', 'ListController@delete');
      Route::post('{id}/done', 'ListController@check');
    });

    Route::group(['prefix' => 'account'], function(){
      Route::get('/', 'AccountController@index');
      Route::post('edit', 'AccountController@edit');
    });
  });



});
