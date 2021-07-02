<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class AlbumController extends Controller
{

    // VIEW - GET
    // list
    public function listAlbumView()
    {
        $albums = DB::table("ALBUM")->get();
        // dd($album);
        return view("album.list", ["albums" => $albums]);
    }
    // VIEW - GET
    // Create
    public function createAlbumView()
    {
        $songList = DB::table('SONG')
            ->join('GENRE', 'GENRE.GE_ID', '=', 'SONG.GE_ID')
            ->get();

        // dd($songList);
        $artistList = DB::table("ARTIST")->get();
        for ($i = 0; $i < sizeof($songList); $i++) {

            $songId = $songList[$i]->SO_ID;

            $artists = DB::table('artist')
                ->join('artist_song', 'artist_song.AR_ID', '=', 'artist.AR_ID')
                ->where('artist_song.SO_ID', '=', $songId)
                ->select(['artist.AR_ID', 'artist.AR_NAME'])
                ->get();
            $songList[$i]->ARTISTS =  $artists;
        }


        return view('album.create', ['songList' => $songList, 'artistList' => $artistList]);
    }

    // VIEW - POST
    // Create
    public function createAlbum(Request $request)
    {
        // dd($request);

        // lấy dữ liệu từ request
        $data = [
            'AL_NAME' => trim($request->album_name, " "), //cắt khoảng trắng 2 bên của tên
            'AR_ID' => $request->album_artist,
            'AL_DES' => $request->album_desc,
            'songsId' => $request->songId,
        ];


        try {

            // Lưu ảnh vô thư mục album
            if ($request->hasFile('album_image')) {
                $imagePath = Storage::putFile('album-image', $request->file('album_image'));
                $imageName = basename(($imagePath)); // trả về tên file
            }

            // if ($request->hasFile('album_image2')) {
            //     $imagePath2 = Storage::putFile('album-image', $request->file('album_image2'));
            //     $imageName2 = basename(($imagePath2)); // trả về tên file
            // }


            // Lưu PLAYLIST DB
            $albumId = DB::table('album')
                ->insertGetId([
                    'AL_NAME' => $data['AL_NAME'],
                    'AR_ID' => $data['AR_ID'],
                    'AL_IMG' => $imageName,
                ]);

            // Lưu AlbumId và SongId vào Collection

            foreach ($data['songsId'] as $songId) {
                DB::table('album_song')
                    ->insert(['AL_ID' => $albumId, 'SO_ID' => $songId]);
            }
        } catch (Exception $ex) {

            Session::flash('fail', 'Lỗi Server');
            dd($ex->getMessage());
            return Redirect::back();
        }

        Session::flash('success', 'Đã Upload');
        return Redirect::to('album/create');
    }
}
