<?php

namespace App\Http\Controllers;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    // GET
    // PLAYLIST LIKED
    public function getPlaylistLiked($user_id)
    {
        try {

            // Lấy id playlit mà người dùng đã like
            $playlistId = DB::table("LIKE_PLAYLIST")->where("US_ID", "=", $user_id)->select('PL_ID')->get();
            // dd($playlistId);

            // Lấy thông tin chi tiết của từng playlist trong danh sách
            $playlist = [];
            $size = sizeof($playlistId);
            for ($i = 0; $i < $size; $i++) {
                $playlistTemp = DB::table("PLAYLIST")->where("PL_ID", "=", $playlistId[$i]->PL_ID)->get();
                array_push($playlist, $playlistTemp[0]);
            }

            // dd($playlist);
            return $playlist;
        } catch (Exception $ex) {
            return response()->json(["result" => "fail", "message" => "Lỗi Máy Chủ"]);
        }
    }

    // GET
    // ALBUM LIKED
    public function getAlbumLiked($user_id)
    {
        try {
            // Lấy id album
            $albumId = DB::table("LIKE_ALBUM")->where("US_ID", "=", $user_id)->select("AL_ID")->get();
            // dd($albumId);

            // Lấy thông tin chi tiết album
            $album = [];
            $size = sizeof($albumId);
            for ($i = 0; $i < $size; $i++) {
                $albumTemp = DB::table("ALBUM")->where("AL_ID", "=", $albumId[$i]->AL_ID)->get();
                array_push($album, $albumTemp[0]);
            }
            // dd($album);
            return $album;
        } catch (Exception $ex) {
            return response()->json(["result" => "fail", "message" => "Lỗi Máy Chủ"]);
        }
    }
}
