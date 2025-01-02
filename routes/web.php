<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

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

//Route::get('/', 'App\Http\Controllers\HomeController@index');

//Route::get('/quests/{slug}', 'QuestsController@show')->name('quests.show');
//Route::get('/cities/{slug}', 'CitiesController@show')->name('cities.show');
//Route::get('/tags/{slug}', 'TagsController@show')->name('tags.show');
//Route::post('/subscribe', 'SubscribeController@subscribe');
//Route::get('/verify/{token}', 'SubscribeController@verify');

Route::group(['middleware' => 'guest', 'namespace' => 'App\Http\Controllers'], function() {
    Route::get('/admin_login', 'AuthController@login')->name('auth.login');
    //Route::post('/registration', 'AuthController@registerUser');
    Route::post('/login', 'AuthController@loginUser');
});


Route::group(['middleware' => 'auth', 'namespace' => 'App\Http\Controllers'], function() {
    Route::get('/logout', 'AuthController@logout');

/*     Route::post('/quests/{slug}/{step}', 'QuestsController@addGame')->name('quests.addGame');
    Route::get('/quests/{slug}/{step}', 'QuestsController@sight')->name('quests.sight');
    Route::post('/quests/{slug}/{step}/check', 'QuestsController@checkAnswer')->name('quests.checkAnswer');
    Route::post('/quests/{slug}/{step}/next', 'QuestsController@nextSight')->name('quests.nextSight');
    Route::post('/quests/{slug}/{step}/hint', 'QuestsController@getHint')->name('quests.getHint');
    Route::post('/quests/{slug}/{step}/skip', 'QuestsController@skipQuestion')->name('quests.skipQuestion');
    Route::post('/quests/{slug}/{step}/mode', 'QuestsController@changeMode')->name('quests.changeMode');

    Route::get('/quests/{slug}/finish', 'QuestsController@finish')->name('quests.finish');
     */

    //Route::get('/quests/{slug}/finish', 'QuestsController@finish')->name('quests.finish');
});

Route::group(['prefix' => 'admin', 'namespace' => 'App\Http\Controllers\Admin', 'middleware' => 'admin'], function() {
    Route::get('/', 'DashboardController@index')->name('admin.dashboard');
     Route::resource('/cities', 'CitiesController', [
        'as' => 'admin'
    ]);
    Route::resource('/users', 'UsersController', [
        'as' => 'admin'
    ]);
    Route::resource('/quests', 'QuestsController', [
        'as' => 'admin'
    ]);
    Route::get('/city/{city_id}/quests', 'QuestsController@city')->name('admin.quests.city');

    Route::get('/sights/create/{quest_id?}', 'SightsController@create')->name('admin.sights.create');
    Route::resource('/sights', 'SightsController', [
        'as' => 'admin'
    ])->only(['index', 'store', 'show', 'edit', 'update', 'destroy']);;
    Route::get('/quest/{quest_id}/sights', 'SightsController@quest')->name('admin.sights.quest');
   

});
