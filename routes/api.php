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

Route::group(['namespace' => 'App\Http\Controllers\Api',], function() {

    Route::post('/auth/login', 'AuthController@login');
    Route::post('/auth/logout', 'AuthController@logout');
    Route::post('/auth/checkEmail', 'AuthController@checkEmail');
    Route::post('/auth/sendCode', 'AuthController@sendCode');
    Route::post('/auth/checkCode', 'AuthController@checkCode');
    Route::post('/auth/changePassword', 'AuthController@changePassword');
    Route::get('/auth/getAuth', 'AuthController@getAuth');

    Route::get('/cities/all', 'CitiesController@index');
    Route::get('/cities/featured', 'CitiesController@featured');
    Route::post('/cities/search', 'CitiesController@search');

    Route::post('/contacts/send', 'ContactsController@send');

    Route::get('/games/get/{quest_id}', 'GamesController@get');
    Route::get('/games/next/{quest_id}', 'GamesController@next');
    Route::post('/games/checkAnswer/{quest_id}', 'GamesController@checkAnswer');
    Route::get('/games/getHint/{quest_id}', 'GamesController@getHint');
    Route::post('/games/getSkip/{quest_id}', 'GamesController@getSkip');
    Route::post('/games/setMode/{quest_id}', 'GamesController@setMode');
    Route::get('/games/getLevel/{quest_id}/{level}', 'GamesController@getLevel');

    Route::get('/modes/', 'ModesController@index');

    Route::get('/pages/about', 'PagesController@about');
    Route::get('/pages/howPlay', 'PagesController@howPlay');

    Route::get('/quests/all/{city_id}', 'QuestsController@index');
    Route::get('/quests/featured', 'QuestsController@featured');
    Route::get('/quests/get/{id}', 'QuestsController@get');
    Route::get('/quests/done', 'QuestsController@done');
    Route::get('/quests/opened', 'QuestsController@opened');

    Route::get('/users/get', 'UsersController@get');
    Route::post('/users/saveName', 'UsersController@saveName');
    Route::post('/users/saveNotes', 'UsersController@saveNotes');
    Route::post('/users/savePassword', 'UsersController@savePassword');

});
