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

class SongController extends Controller
{
    // VIEW
    // Xem danh sách 
    // GET
    public function viewList()
    {
        $songList = DB::table('SONG')
            ->join('GENRE', 'GENRE.GE_ID', '=', 'SONG.GE_ID')
            ->join('ARTIST', 'ARTIST.AR_ID', 'SONG.AR_ID')
            ->paginate(10);

        // dd($songList);

        return view('song.list', ['songList' => $songList]);
    }

    // VIEW
    // Tạo bài hát
    // GET
    public function viewCreate()
    {

        $genreList = DB::table('GENRE')->get();
        $artistList = DB::table('ARTIST')->get();

        // dd($genreList, $artistList);

        return view('song.create', ['genreList' => $genreList, 'artistList' => $artistList]);
    }

    // VIEW
    // Tạo bài hát
    // POST

    public function create(Request $request)
    {

        // dd($request);

        $data = [
            'song_name' => trim($request->song_name, " "), //cắt khoảng trắng 2 bên của tên
            'GE_ID' => $request->genre_id,
            'AR_ID' => $request->artist_id
        ];

        try {

            // Lưu ảnh vô thư mục song: public/storage/song-image/<image_name>
            if ($request->hasFile('song_image')) {
                $imagePath = Storage::putFile('song-image', $request->file('song_image'));
                $imageName = basename(($imagePath)); // trả về tên file
            }

            // Lưu file nhạc vô thư mục public/storeage/song/
            if ($request->hasFile('song_mp3')) {
                $songPath = Storage::putFile('song', $request->file('song_mp3'));
                $songName = basename($songPath);
            }

            // Lưu db
            DB::table('song')
                ->insert([
                    'SO_NAME' => $data['song_name'],
                    'GE_ID' => $data['GE_ID'],
                    'AR_ID' => $data['AR_ID'],
                    'SO_SRC' => $songName,
                    'SO_IMG' => $imageName
                ]);
        } catch (Exception $ex) {
            Session::flash('fail', 'Lỗi Server');
            dd($ex->getMessage());
            return Redirect::back();
        }

        Session::flash('success', 'Đã Upload');
        return Redirect::to('song');
    }


    // Lấy Thông tin Bài Hát
    // GET
    public function GetSong(Request $request, $id)
    {
        $host = $request->getHttpHost();
        $song = DB::table('SONG')->where('SO_ID', '=', $id)->first();

        return response()->json($song);
    }

    // GET
    // Lấy thông tin 5 bài hát mới
    public function GetNew()
    {
        $songList = DB::table('SONG')
            ->orderBy('SO_ID', 'desc')
            ->limit(4)
            ->get();

        return $songList;
    }

    // GET
    // Lấy thông tin danh sách bài hát liên quan 
    public function getListSongRelated(Request $request, $word) {
        $host = $request->getHttpHost();
        $songList = DB::table('SONG')
                ->where('SO_NAME', 'LIKE', ''.$word.'%')
                ->get();
        return $songList;
    }

    // GET
    // Lấy thông tin bài hát bằng tên bài hát
    public function getSongInfoByName(Request $request, $name) {
        $host = $request->getHttpHost();
        $listsong = DB::table('SONG')
                ->where('SO_NAME', '=' , $name);
        
        return $listsong;
    }
}
