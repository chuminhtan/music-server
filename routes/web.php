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

use App\Http\Controllers\SongController;

Route::get('/', 'SongController@viewList');

Route::group(['prefix' => 'song'], function () {
    Route::get('', 'SongController@viewList');
    Route::get('/create', 'SongController@viewCreate');
    Route::post('/create', 'SongController@create');
    Route::get('/new', "SongController@GetNew");
    Route::get('/{id}', 'SongController@GetSong');
    Route::get('/search/{name}', 'SongController@getSongInfoByName');
    Route::get('/songrelate/{word}', 'SongController@getListSongRelated');
});
