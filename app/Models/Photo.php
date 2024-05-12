<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

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
        $extension = strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));

        if (in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {
            return url($appURL . '/storage/videos/' . $this->filename);
        } else {
            return url($appURL . '/storage/images/' . $this->filename);
        }
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
