<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CollectionController extends Controller
{
    // Playlist theo ID
    // Get
    public function getCollectionByPlaylistId($playlistId)
    {

        try {
            $playlist = DB::table('playlist')->where('PL_ID', '=', $playlistId)->first();
            // $songs = DB::table('collection')
            //     ->join('song', 'song.SO_ID', '=', 'collection.SO_ID')
            //     ->join('artist_song', 'artist_song.SO_ID', 'song.SO_ID')
            //     ->join('artist', 'artist_song.AR_ID', 'artist.AR_ID')
            //     ->where('collection.PL_ID', '=', $playlistId)
            //     ->select(['song.*', 'artist.AR_ID', 'artist.AR_NAME'])
            //     ->groupBy('SO_ID')
            //     ->get();

            $songs = DB::table('collection')
                ->join('song', 'song.SO_ID', '=', 'collection.SO_ID')
                ->where('PL_ID', '=', $playlistId)
                ->get();

            for ($i = 0; $i < sizeof($songs); $i++) {
                $songId = $songs[$i]->SO_ID;

                $artists = DB::table('artist')
                    ->join('artist_song', 'artist_song.AR_ID', '=', 'artist.AR_ID')
                    ->where('artist_song.SO_ID', '=', $songId)
                    ->select(['artist.AR_ID', 'artist.AR_NAME'])
                    ->get();


                $songs[$i]->ARTISTS =  $artists;
            }


            return response()->json(['playlist' => $playlist, 'songs' => $songs]);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
