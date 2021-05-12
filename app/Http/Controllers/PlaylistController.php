<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use function Psy\debug;

class PlaylistController extends Controller
{


    // Playlist Mới Nhất
    // Get
    public function getNewest()
    {

        try {

            $playlistNewest = DB::table('playlist')->limit(4)->get();

            return $playlistNewest;
        } catch (Exception $err) {
            return response()->json(['errors' => $err->getMessage()]);
        }
    }
}
