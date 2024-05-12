<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $albums = Gallery::all();

        // 各アルバムに対して1枚の写真を取得する
        $albums->each(function ($album) {
            $album->photo = Photo::where('album_id', $album->id)
                ->orderBy('created_at', 'desc')
                ->first();
        });

        $data = [
            'albums' => $albums,
        ];

        return view('gallery.index-gallery', $data);
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
        $validated = $request->validate([
            'album_name' => 'required',
        ]);

        $album_name = $validated['album_name'];

        // 投稿内容をDBに保存
        auth()->user()->gallery()->create([
            'album_name' => $album_name,
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        // アルバム内にある写真を取得してファイルを削除
        $photos = Photo::where('album_id', $gallery->id)->get();
        foreach ($photos as $photo) {
            Storage::delete('public/images/'. $photo->filename);
        }

        // DBからレコードを削除（関連する写真も自動的に削除される）
        $gallery->delete();

        return back();
    }
}
