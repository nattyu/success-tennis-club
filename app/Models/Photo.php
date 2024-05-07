<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['filename', 'album_id'];
    protected $appends = ['photo_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPhotoUrlAttribute()
    {
        // APP_URL の値を取得し、ファイル名に追加する
        $appURL = env('APP_URL');
        return url($appURL . '/storage/images/' . $this->filename);
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
