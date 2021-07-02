<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ArtistController extends Controller
{

    // VIEW - GET
    // List
    public function listArtistView()
    {

        try {
            $artistList = DB::table("ARTIST")->get();

            // dd($artistList);
            return view("artist.list", ["artistList" => $artistList]);
        } catch (Exception $ex) {
            Session::flash('fail', 'Lỗi Server');
            return Redirect::back();
        }
    }

    // VIEW - GET
    // Create
    public function createArtistView()
    {
        return view("artist.create");
    }

    // VIEW - POST
    // Create
    public function createArtist(Request $request)
    {

        try {

            // Lưu ảnh vô thư mục artist: public/storage/artist-image/<image_name>
            if ($request->hasFile('artist_image')) {
                $imagePath = Storage::putFile('artist-image', $request->file('artist_image'));
                $imageName = basename(($imagePath)); // trả về tên file
            }

            // Lưu vào db
            DB::table("ARTIST")->insert([
                "AR_NAME" => trim($request->artist_name, " "),
                "AR_STORY" => trim($request->artist_des, " "),
                "AR_IMG" => $imageName
            ]);
            Session::flash('success', 'Đã Upload');
            return Redirect::to("artist/create");
        } catch (Exception $ex) {
            Session::flash('fail', 'Lỗi Server');
            return Redirect::back();
        }
    }
}
