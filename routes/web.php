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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Session;

Auth::routes(['verify' => true]);



Route::get('/auctions/{action}', 'Api\V1\AuctionsController@index');
Route::resource('/federalDistricts', 'Api\V1\FederalDistrictsController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/regions', 'Api\V1\RegionsController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/stores', 'Api\V1\StoresController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/products', 'Api\V1\ProductsController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/purposes', 'Api\V1\PurposesController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/capacities', 'Api\V1\CapacitiesController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/tags', 'Api\V1\TagsController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/multiplicities', 'Api\V1\MultiplicitiesController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/types', 'Api\V1\TypesController', ['except' => ['create', 'edit', 'update', 'delete']]);


Route::get('/storesAll', 'Api\V1\StoresController@all');

Route::get('/', function () {
    return redirect('/personal');
});

Route::group([
    'prefix' => 'personal',
    'middleware' => ['verified', 'contragent'],
], function () {
    Route::any('/', 'PersonalController@index');
    Route::post('/invoice', 'PersonalController@invoice');
    Route::get('/invoice/{bet_id}', 'PersonalController@invoiceNew');
    Route::get('/contracts/pdf/{id}', 'PersonalController@contract');
    Route::any('/{controller}', 'PersonalController@index');
    Route::any('/{controller}/{action}', 'PersonalController@index');
    Route::any('/{controller}/{action}/{id}', 'PersonalController@index');
});

Route::group([
    'prefix' => '/web/v1',
    'middleware' => ['verified'],
    'namespace' => 'Api\V1'
], function () {
    Route::resource('contragents', 'ContragentsController', ['except' => ['edit', 'create']]);
    Route::resource('messages', 'MessagesController', ['except' => ['edit', 'create']]);

    Route::get('company', 'ContragentsController@my');
    Route::get('staff', 'ContragentsController@staff');
    Route::patch('company', 'ContragentsController@updateCompany');

    Route::post('address', 'RegionsController@address');

    Route::get('show_phone/{id}', 'LogisticsController@showPhone');
    

    Route::post('auctions/guarantee', 'AuctionsController@guaranteeBet');

    Route::post('auctions/bet', 'AuctionsController@bet');
    Route::get('auctions/unbet/{id}', 'AuctionsController@unbet');
    Route::post('auctions', 'AuctionsController@store');
    Route::post('auctions/{id}', 'AuctionsController@update');
    Route::get('auction/{id}', 'AuctionsController@show');
    Route::get('auctions/{action}', 'AuctionsController@index');
    Route::get('auctions/bet/remove/{id}', 'AuctionsController@betRemove');
    Route::post('auctions/bet/contract', 'AuctionsController@approveContract');
    Route::post('auctions/bet/uncontract', 'AuctionsController@unApproveContract');
    Route::get('auctions/bet/volume/{id}', 'AuctionsController@approveVolume');
    Route::get('auctions/bet/unvolume/{id}', 'AuctionsController@unApproveVolume');
    Route::get('auctions/bid/{id}', 'AuctionsController@bid');
    Route::get('auctions/unbid/{id}', 'AuctionsController@unbid');
    Route::post('auctions/bidder/toggle', 'AuctionsController@toggleBidder');

    Route::post('auction/copy', 'AuctionsController@copy');
    Route::get('auction/delete/{id}', 'AuctionsController@destroy');
    Route::get('auction/confirm/{id}', 'AuctionsController@confirm');
    Route::post('addbidder', 'AuctionsController@addBidder');
    Route::post('settlements', 'SettlementsController@pay');
    Route::get('settlements', 'SettlementsController@index');

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

    Route::post('results', 'ResultsController@update');
    Route::get('results', 'ResultsController@index');
    Route::get('results/purchases', 'ResultsController@purchases');

    Route::post('sendmail', 'MailController@send');


    Route::resource('defers', 'DefersController', ['except' => ['edit', 'create']]);
    Route::resource('logistics', 'LogisticsController', ['except' => ['edit', 'create']]);



    Route::get('disputes', 'DisputesController@index');
    Route::patch('disputes', 'DisputesController@store');
    Route::get('disputes/{id}', 'DisputesController@show');
    Route::post('disputes/{id}/line', 'DisputesController@lineStore');
    Route::patch('disputes/{id}/line/{line_id}', 'DisputesController@lineUpdate');
    Route::delete('disputes/{id}/line/{line_id}', 'DisputesController@lineDelete');
    Route::post('disputes/{id}/proposal', 'DisputesController@proposalStore');
    Route::patch('disputes/{id}/proposal/{proposal_id}', 'DisputesController@proposalUpdate');
    Route::delete('disputes/{id}/proposal/{proposal_id}', 'DisputesController@proposalDelete');

    Route::patch('disputes/{id}/proposal/{proposal_id}/vote', 'DisputesController@toggleVote');
    Route::patch('disputes/{id}/emails', 'DisputesController@sendEmails');

    Route::patch('contracts/sign/{id}', 'ContractsController@sign');
    Route::patch('contracts/unsign/{id}', 'ContractsController@unsign');

    Route::patch('contracts/signMy/{id}', 'ContractsController@signMy');
    Route::patch('contracts/unsignMy/{id}', 'ContractsController@unsignMy');

    Route::get('contractTemplates/get', 'ContractTemplatesController@get');
    Route::get('contractTemplates/contragent/{id}', 'ContractTemplatesController@contragent');
    Route::resource('contractTemplates', 'ContractTemplatesController', ['except' => ['edit', 'create']]);
    Route::get('contracts/my', 'ContractsController@my');
    Route::get('contracts/tome', 'ContractsController@tome');
    Route::resource('contracts', 'ContractsController', ['except' => ['edit', 'create']]);
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('auth', function () {
    if (Auth::user()) {
        return [
            'user' => Auth::user(),
            'api_token' => Session::get('_api_token')
        ];
    }
});

//Переключение языков
// Route::get('setlocale/{lang}', function ($lang) {

//     $referer = Redirect::back()->getTargetUrl(); //URL предыдущей страницы
//     $parse_url = parse_url($referer, PHP_URL_PATH); //URI предыдущей страницы

//     //разбиваем на массив по разделителю
//     $segments = explode('/', $parse_url);

//     //Если URL (где нажали на переключение языка) содержал корректную метку языка
//     if (in_array($segments[1], App\Http\Middleware\LocaleMiddleware::$languages)) {

//         unset($segments[1]); //удаляем метку
//     } 
    
//     //Добавляем метку языка в URL (если выбран не язык по-умолчанию)
//     if ($lang != App\Http\Middleware\LocaleMiddleware::$mainLanguage){ 
//         array_splice($segments, 1, 0, $lang); 
//     }

//     //формируем полный URL
//     $url = Request::root().implode("/", $segments);
    
//     //если были еще GET-параметры - добавляем их
//     if(parse_url($referer, PHP_URL_QUERY)){    
//         $url = $url.'?'. parse_url($referer, PHP_URL_QUERY);
//     }
//     return redirect($url); //Перенаправляем назад на ту же страницу                            

// })->name('setlocale');


// Route::group(['prefix' => App\Http\Middleware\LocaleMiddleware::getLocale()], function(){
//     ..
//     });




// <a href="{{ route('home') }}">Home</a>

// Если атрибут href указывается с помощью функции-помошника url() (или любым другим образом), то в начале передаем текущий язык приложения:
// <a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale() .'/home') }}">Home</a>
// или
// <a href="{{ App\Http\Middleware\LocaleMiddleware::getLocale() .'/home' }}">Home</a>