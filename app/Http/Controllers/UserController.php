<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    // GET
    // Lấy danh sách bài hát đã Like
    public function getLikedSong($userId)
    {

        $songs = DB::table('LIKE_SONG')
            ->join("SONG", "SONG.SO_ID", "LIKE_SONG.SO_ID")
            ->where("LIKE_SONG.US_ID", '=', $userId)
            ->get();

        return $songs;
    }

    // GET
    // Lấy Playlist đã like
    public function getLikedPlaylist($userId)
    {
        $playlists = DB::table('LIKE_PLAYLIST')
            ->join('SONG', 'SONG.SO_ID', 'LIKE_PLAYLIST.SO_ID')
            ->where("LIKE_PLAYLIST.SO_ID", "=", $userId)
            ->get();

        return $playlists;
    }

    // GET
    // Lấy Album đã like
    public function getLikedAlbum($userId)
    {
        $playlists = DB::table('LIKE_ALBUM')
            ->join('SONG', 'SONG.SO_ID', 'LIKE_ALBUM.SO_ID')
            ->where("LIKE_ALBUM.US_ID", "=", $userId)
            ->get();

        return $playlists;
    }


    // GET
    // Lấy Danh Sách Playlist mà USER_ID đã tạo
    public function getPlaylistByUserId($userId)
    {

        $playlists = DB::table("playlist")->where("US_ID", '=', $userId)->get();

        return $playlists;
    }
}
