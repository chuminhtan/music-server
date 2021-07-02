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
});



// COLLECTION

Route::group(['prefix' => 'collection'], function () {
    Route::get('/{playlist_id}', 'CollectionController@getCollectionByPlaylistId'); // Lấy danh sách tất cả bài hát của 1 playlist bằng playlist_id

});


// USER
Route::group(['prefix' => 'user'], function () {

    // api
    Route::get('/liked-song/{user_id}', 'UserController@getLikedSong'); // Lấy danh sách tất cả bài hát của 1 playlist bằng playlist_id
    Route::get('/liked-playlist/{user_id}', 'UserController@getLikedPlaylist'); // Lấy danh sách tất cả bài hát của 1 playlist bằng playlist_id
    Route::get('/playlist/{userId}', 'UserController@getPlaylistByUserId');
    Route::get('/liked/playlist/{user_id}', 'UserController@getPlaylistLiked'); // Lấy danh sách đã like
    Route::get('/liked/album/{user_id}', 'UserController@getAlbumLiked'); // lấy danh sách album đã like

    Route::post('/register', 'UserController@register');
    Route::post('/login', 'UserController@login');
});

// ARTIST
Route::group(['prefix' => 'artist'], function () {
    // view
    Route::get('/', "ArtistController@listArtistView");
    Route::get('/create', "ArtistController@createArtistView");
    Route::post('/create', "ArtistController@createArtist");

    // api


});
