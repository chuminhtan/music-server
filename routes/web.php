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


// SONG

Route::group(['prefix' => 'song'], function () {
    Route::get('', 'SongController@viewList');
    Route::get('/create', 'SongController@viewCreate');
    Route::post('/create', 'SongController@create');
    Route::get('/new', "SongController@GetNew");
    Route::get('/{id}', 'SongController@GetSong');
});



// PLAYLIST

Route::group(['prefix' => 'playlist'], function () {
    Route::get('/newest', 'PlaylistController@getNewest');
    Route::get('/{id}', 'PlaylistController@getPlaylist');
});



// COLLECTION

Route::group(['prefix' => 'collection'], function () {
    Route::get('/{playlist_id}', 'CollectionController@getCollectionByPlaylistId'); // Lấy danh sách tất cả bài hát của 1 playlist bằng playlist_id

});
