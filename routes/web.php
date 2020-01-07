<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();


Route::group(['prefix' => '/manager', 
   'namespace' => 'Manager'
], function () {
    Route::get('contragents','ContragentsController@getIndex');
    Route::post('contragents','ContragentsController@postStore');
    Route::get('contragents/data','ContragentsController@getData');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
