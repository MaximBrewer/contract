<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/v1', 
   'namespace' => 'Api\V1', 'as' => 'api.'
], function () {
    Route::resource('contragents', 'ContragentsController', ['except' => ['edit', 'create']]);
    Route::resource('auctions', 'AuctionsController', ['except' => ['edit', 'create']]);
    Route::resource('federalDistricts', 'FederalDistrictsController', ['except' => ['create', 'edit', 'update', 'delete']]);
    Route::resource('regions', 'RegionsController', ['except' => ['create', 'edit', 'update', 'delete']]);
    Route::resource('stores', 'StoresController', ['except' => ['create', 'edit', 'update', 'delete']]);
    Route::resource('products', 'ProductsController', ['except' => ['create', 'edit', 'update', 'delete']]);
    Route::resource('multiplicities', 'MultiplicitiesController', ['except' => ['create', 'edit', 'update', 'delete']]);
    Route::resource('types', 'TypesController', ['except' => ['create', 'edit', 'update', 'delete']]);
});