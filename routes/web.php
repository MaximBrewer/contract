<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Session;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|

<a href="{{ route('home') }}">Home</a>

Если атрибут href указывается с помощью функции-помошника url() (или любым другим образом), то в начале передаем текущий язык приложения:
<a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale() .'/home') }}">Home</a>
или
<a href="{{ App\Http\Middleware\LocaleMiddleware::getLocale() .'/home' }}">Home</a>


*/

// Route::group(['prefix' => App\Http\Middleware\LocaleMiddleware::getLocale()], function(){
//     ..
//     });
Auth::routes(['verify' => true]);


Route::get('/auctions/{action}', 'Api\V1\AuctionsController@index');
Route::resource('/federalDistricts', 'Api\V1\FederalDistrictsController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/regions', 'Api\V1\RegionsController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/stores', 'Api\V1\StoresController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/products', 'Api\V1\ProductsController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/multiplicities', 'Api\V1\MultiplicitiesController', ['except' => ['create', 'edit', 'update', 'delete']]);
Route::resource('/types', 'Api\V1\TypesController', ['except' => ['create', 'edit', 'update', 'delete']]);

Route::get('/home', function () {
    return redirect('/personal');
});
Route::get('/', function () {
    return redirect('/personal');
});

Route::get('/personal', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/contragents', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/contragents/create', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/contragents/edit/{id}', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/contragents/show/{id}', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/auctions', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/auctions/archive', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/auctions/my', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/auctions/bid', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/auctions/create', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/company', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/auctions/edit/{id}', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/auctions/show/{id}', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/dialogue/show/{id}', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/dialogues', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');

Route::get('/personal/targets', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/targets/create', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');
Route::get('/personal/targets/edit/{id}', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');

Route::get('/personal', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');

Route::get('/personal/contragents/reviews', 'PersonalController@index')->middleware('verified')->middleware('contragent')->name('personal');


// Route::get('/about', 'AboutController@index')->name('about');
// Route::get('/rating', 'RatingController@index')->name('rating');
// Route::get('/auction', 'AuctionController@index')->name('auction');

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
