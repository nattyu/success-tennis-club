<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'elected_court_id',
        'attend_flg',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function elected_court() {
        return $this->belongsTo(PostCourt::class);
    }
}
