<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photos = Photo::all();
        $data = [
            'photos' => $photos,
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'file.*' => 'required|file|image|mimetypes:image/jpeg,image/png',
        ]);

        // 複数のファイルを処理
        foreach ($request->file('file') as $file) {
            $ext = $file->getClientOriginalExtension();
            // ファイル名の衝突を避けるためランダムな数値を追加
            $filename = time() . rand(1, 100) . "." . $ext;
            $file->storeAs('public/images', $filename);

            // 投稿内容をDBに保存
            auth()->user()->photos()->create([
                'filename' => $filename,
            ]);
        }

        return back();
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
