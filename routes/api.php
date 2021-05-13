<?php

use Illuminate\Http\Request;


Route::group(['prefix' => 'song'], function () {
    Route::get('', 'SongController@apiList');
    Route::get('/{id}', 'SongController@apiGetSong');
    Route::get('/new', "SongController@apiGetNew");
    Route::get('/search/{name}', "SongController@apigetSongInfoByName");
    Route::get('/songrelate', "SongController@apigetListSongRelated");
});
