<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maestroerror\HeicToJpg;
use Illuminate\Support\Facades\Session;


class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($album_id)
    {
        $photos = Photo::where('album_id', $album_id)->get();
        $data = [
            'photos' => $photos,
            'album_id' => $album_id,
        ];
        return view('photo.index-photo', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $album_id)
    {
        try {
            $this->validate($request, [
                'file.*' => 'required|file',
            ]);

            foreach ($request->file('file') as $file) {                
                $ext = strtolower($file->getClientOriginalExtension());
                $filename = time() . rand(1, 100);

                if ($ext === 'heic') {
                    // HEICファイルをJPEGに変換
                    $filename .= '.jpg';
                    try {
                        HeicToJpg::convert($file->getPathname())->saveAs(storage_path('app/public/images/' . $filename));
                    } catch (\Exception $e) {
                        \Log::error("Error converting HEIC to JPG: " . $e->getMessage());
                        continue;  // 変換エラーが発生したファイルはスキップ
                    }
                } else {
                    // その他のファイルはそのまま保存
                    $filename .= "." . $ext;
                    $file->storeAs('public/images', $filename);
                }

                // 投稿内容をDBに保存
                auth()->user()->photos()->create([
                    'filename' => $filename,
                    'album_id' => $album_id,
                ]);
            }

            return back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // バリデーションエラーの詳細をログに記録
            \Log::error("Validation error: " . $e->getMessage());
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            // その他のエラー
            \Log::error("An error occurred: " . $e->getMessage());
            Session::flash('error', "An error occurred, please try again.");
            return back();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        // 画像ファイルを削除
        Storage::delete('public/images/'. $photo->filename);
        // DBからレコードを削除
        $photo->delete();
        return back();
    }
}
