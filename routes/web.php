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

<a href="{{ route('home') }}">Home</a>

Если атрибут href указывается с помощью функции-помошника url() (или любым другим образом), то в начале передаем текущий язык приложения:
<a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale() .'/home') }}">Home</a>
или
<a href="{{ App\Http\Middleware\LocaleMiddleware::getLocale() .'/home' }}">Home</a>


*/

// Route::group(['prefix' => App\Http\Middleware\LocaleMiddleware::getLocale()], function(){
//     ..
//     });


Route::get('/home', function () {
    return redirect('/');
});


Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/personal', 'PersonalController@index')->name('personal');
Route::get('/about', 'AboutController@index')->name('about');
Route::get('/rating', 'RatingController@index')->name('rating');
Route::get('/auction', 'AuctionController@index')->name('auction');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
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