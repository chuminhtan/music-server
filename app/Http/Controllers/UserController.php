<?php

namespace App\Http\Controllers;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
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

    // POST
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'user_name' => 'required|min:6|max:100',
            'user_email' => 'required|email',
            'user_password' => 'required|min:6',
        ], [
            'required' => ':attribute Trống',
            'email' => ':attribute Email Không Hợp Lệ',
            'min' => ':attribute Có Ít Nhất 6 Ký Tự',
            'max:100' => 'attribute Có Tối Đa 100 Ký Tự'
        ], [
            'user_name' => 'Tên Người Dùng',
            'user_email' => 'Email',
            'user_password' => 'Mật khẩu',
        ]);

        if ($validator->fails()) {
            // return redirect('post/create')
            //             ->withErrors($validator)
            //             ->withInput();

            return response()->json(["result" => "fail", "message" => $validator->getMessageBag()]);
        }



        try {
            // Kiểm tra email đã tồn tại chưa
            $users = DB::table('USER')->where('US_EMAIL', '=', $request->user_email)->get();

            if (count($users) > 0) {
                return response()->json(["result" => "fail", "message" => "Email đã được đăng kí"]);
            }

            // Lưu vào DB
            DB::table('USER')->insert([
                'US_NAME' => $request->user_name,
                'US_EMAIL' => $request->user_email,
                'US_PASS' => bcrypt($request->user_password),
                'US_TYPE' => 1
            ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
            return response()->json(["result" => "fail", "message" => "Lỗi Máy Chủ"]);
        }
        return response()->json(["result" => "success", "message" => "Đăng Kí Thành Công"]);
    }


    //POST
    // LOGIN
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'user_email' => 'required|email',
            'user_password' => 'required|min:6',
        ], [
            'required' => ':attribute Trống',
            'email' => ':attribute Email Không Hợp Lệ',
            'min' => ':attribute Có Ít Nhất 6 Ký Tự',
        ], [
            'user_email' => 'Email',
            'user_password' => 'Mật khẩu',
        ]);

        if ($validator->fails()) {
            return response()->json(["result" => "fail", "message" => $validator->getMessageBag()]);
        }


        // kiểm tra người dùng có tồn tại ??
        $user = DB::table('USER')->where('US_EMAIL', '=', $request->user_email)->first();

        if ($user == null) {
            return response()->json(["result" => "fail", "message" => "Đăng Nhập Không Thành Công"]);
        }

        // kiểm tra mật khẩu có đúng ??
        $comparePassword = Hash::check($request->user_password, $user->US_PASS);

        if ($comparePassword == false) {
            return response()->json(["result" => "fail", "message" => "Đăng Nhập Không Thành Công"]);
        }

        unset($user->US_PASS);
        unset($user->US_TYPE);

        // Lưu thời gian đăng nhập vào db
        return response()->json(["result" => "success", "message" => "Đăng Nhập Thành Công", "user" => $user]);
    }
    
    // POST
    // Lưu thông tin lượt thích của người dùng
    public function like(Request $request){

        // Debug
        // dd($request->user_id, $request->song_id);

        try {
            //Kiểm tra người dùng đã like bài hát chưa
            $users = DB::table('LIKE_SONG')->where('US_ID', '=', $request->user_id)
                                            ->where("SO_ID", "=", $request->song_id)
                                            ->get();

            if (count($users) > 0) {
                DB::table('LIKE_SONG')->where('US_ID', '=', $request->user_id)
                                    ->where("SO_ID", "=", $request->song_id)
                                    ->delete();            
                return response()->json(["result" => "success", "message" => "unlike"]);
            } 

            // Lưu vào DB
            DB::table('LIKE_SONG')->insert([
                'US_ID' => $request->user_id,
                'SO_ID' => $request->song_id
            ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
            return response()->json(["result" => "fail", "message" => "Lỗi Máy Chủ"]);
        }
        return response()->json(["result" => "success", "message" => "like"]);
    }

    // GET
    // Kiểm tra bài hát đã được like hay chưa
    public function checkLikeSong($userId, $songId)
    {
        //dd($userId, $songId);
        $like_song = DB::table("LIKE_SONG")->where("US_ID", '=', $userId)
                                            ->where("SO_ID","=", $songId)
                                            ->get();
        return $like_song;
    }

    // POST
    // Add New User Playlist
    public function createNewPlaylist(Request $request){
        try {
            // Retrieve img file from public/storage/song-image/<image_name>
            $imgfile = Storage::path('song-image/' . $request->song_img);
            //Save img in: public/storage/playlist-image/<image_name>
            $imagePath = Storage::putFile('playlist-image', new \Illuminate\Http\File($imgfile));
            $imageName = basename(($imagePath));

            //Add New User Playlist
            $id = DB::table('PLAYLIST')->insertGetId([
                'US_ID' => $request->user_playlist_use_id,
                'PL_NAME' => $request->user_playlist_name,
                'PL_TYPE' => $request->user_playlist_type,
                'PL_IMG' => $imageName
            ]);
            //Add Song to Playlist
            DB::table('COLLECTION')->insert([
                'PL_ID' => $id,
                'SO_ID' => $request->song_id
            ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
            return response()->json(["result" => "fail", "message" => "Error"]);
        }
        return response()->json(["result" => "success", "message" => "create new user playlist successfully"]);
    }

    //POST
    // Add Song to User Playlist
    public function addSongToUserPlaylist(Request $request){
        try {
            // Add Song to Playlist
            DB::table('COLLECTION')->insert([
                'PL_ID' => $request->user_playlist_id,
                'SO_ID' => $request->user_playlist_song_id
            ]);
        } catch (Exception $ex) {
            return response()->json(["result" => "fail", "message" => 'error']);
        }
        return response()->json(["result" => "success", "message" => "Add song to user playlist successfully"]);
    }
}
