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

    // view
    Route::get('', 'SongController@viewList');
    Route::get('/create', 'SongController@viewCreate');
    Route::post('/create', 'SongController@create');


    //api
    Route::get('/new', "SongController@GetNew");
    Route::get('/{id}', 'SongController@GetSong');
    Route::get('/search/{name}', 'SongController@getSongInfoByName');
    Route::get('/songrelate/{word}', 'SongController@getListSongRelated');
});



// PLAYLIST

Route::group(['prefix' => 'playlist'], function () {

    //view
    Route::get('', 'PlaylistController@showListView');
    Route::get('/create', 'PlaylistController@createPlaylistView');
    Route::post('/create', 'PlaylistController@createPlaylist');


    // api
    Route::get('/newest', 'PlaylistController@getNewest');
    Route::get('/type/{type}', 'PlaylistController@getPlaylistByType');
    Route::get('/user/{userId}', 'PlaylistController@getPlaylistByUserId');
});



// COLLECTION

Route::group(['prefix' => 'collection'], function () {
    Route::get('/{playlist_id}', 'CollectionController@getCollectionByPlaylistId'); // Lấy danh sách tất cả bài hát của 1 playlist bằng playlist_id

});
