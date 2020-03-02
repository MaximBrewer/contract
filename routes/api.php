<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'prefix' => '/v1',
    'middleware' => ['auth:api'],
    'namespace' => 'Api\V1', 'as' => 'api.'
], function () {
    Route::resource('contragents', 'ContragentsController', ['except' => ['edit', 'create']]);
    Route::resource('messages', 'MessagesController', ['except' => ['edit', 'create']]);

    Route::get('company', 'ContragentsController@my');
    Route::patch('company', 'ContragentsController@updateCompany');
    Route::post('address', 'RegionsController@address');

    Route::post('auctions', 'AuctionsController@store');
    Route::post('auctions/{id}', 'AuctionsController@update');
    Route::get('auction/{id}', 'AuctionsController@show');
    Route::get('auctions/{action}', 'AuctionsController@index');

    Route::post('auctions/bet', 'AuctionsController@bet');
    Route::get('auctions/bet/remove/{id}', 'AuctionsController@betRemove');
    Route::post('auctions/bet/contract', 'AuctionsController@approveContract');
    Route::get('auctions/bet/volume/{id}', 'AuctionsController@approveVolume');
    Route::post('auction/copy', 'AuctionsController@copy');
    Route::get('auction/delete/{id}', 'AuctionsController@destroy');
    Route::get('auction/confirm/{id}', 'AuctionsController@confirm');
    Route::get('auctions/{action}/bid/{id}', 'AuctionsController@bid');
    Route::get('auctions/{action}/unbid/{id}', 'AuctionsController@unbid');
    Route::post('addbidder', 'AuctionsController@addBidder');
    Route::post('auctions/bidder/toggle', 'AuctionsController@toggleBidder');


    Route::get('dialogues', 'DialoguesController@index');
    Route::get('dialogues/check/{contragent_id}', 'DialoguesController@storeDialog');

    Route::get('dialogue/{id}', 'DialoguesController@show');
    Route::post('dialogues', 'DialoguesController@store');
    Route::get('targets/all', 'TargetsController@all');
    Route::resource('targets', 'TargetsController', ['except' => ['edit', 'create']]);

    Route::get('comments/{contragentId}', 'CommentController@show');
    Route::post('comments', 'CommentController@store');
    Route::post('commentsmy', 'CommentController@storeMy');
    Route::get('comments', 'CommentController@index');
    Route::post('comments/{commentId}/{type}', 'CommentController@update');
});
